<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Form;
use App\Models\FormItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FormItemController extends Controller
{
    /** Komponen standar (tiga bagian utama). */
    private const CANONICAL_COMPONENTS = ['petunjuk', 'item', 'tambahan'];

    /** Form nama mengandung teks ini: bagian item dikunci di UI; skor maksimal bisa diedit (nilai awal tambah item = 0). */
    private static function isLearningDeviceAssessmentForm(Form $form): bool
    {
        return str_contains(strtolower((string) $form->name), 'perangkat pembelajaran');
    }

    public function __construct()
    {
        $this->middleware('permission:forms.items-read', ['only' => ['index', 'create', 'edit']]);
        $this->middleware('permission:forms.items-create', ['only' => ['store']]);
        $this->middleware('permission:forms.items-update', ['only' => ['update', 'reorder']]);
        $this->middleware('permission:forms.items-delete', ['only' => ['destroy']]);
    }

    /**
     * Daftar item untuk satu form (tanpa halaman global /konfigurasi/formitems).
     */
    public function index(Form $form)
    {
        $sectionLabels = [
            'petunjuk' => 'Petunjuk',
            'item' => 'Item',
            'tambahan' => 'Tambahan',
        ];

        $groupedRows = [];
        foreach (self::CANONICAL_COMPONENTS as $key) {
            $groupedRows[$key] = FormItem::query()
                ->where('form_id', $form->getKey())
                ->where('component', $key)
                ->orderBy('component_order')
                ->orderBy('id')
                ->get();
        }

        $otherItems = FormItem::query()
            ->where('form_id', $form->getKey())
            ->whereNotIn('component', self::CANONICAL_COMPONENTS)
            ->orderBy('component')
            ->orderBy('component_order')
            ->orderBy('id')
            ->get();

        $sectionCreateUrls = [];
        foreach (self::CANONICAL_COMPONENTS as $key) {
            $sectionCreateUrls[$key] = url('/konfigurasi/forms/'.$form->getKey().'/items/create').'?component='.$key;
        }

        return view('konfigurasi.form-items', [
            'form' => $form,
            'sectionLabels' => $sectionLabels,
            'groupedRows' => $groupedRows,
            'otherItems' => $otherItems,
            'itemsResourceBase' => '/konfigurasi/forms/'.$form->getKey().'/items',
            'formsItemsReorderUrl' => url('/konfigurasi/forms/'.$form->getKey().'/items/reorder'),
            'enableFormItemsReorder' => auth()->user()?->can('forms.items-update') ?? false,
            'sectionCreateUrls' => $sectionCreateUrls,
        ]);
    }

    public function create(Form $form, Request $request)
    {
        $component = (string) $request->query('component', 'petunjuk');
        if (! in_array($component, self::CANONICAL_COMPONENTS, true)) {
            $component = 'petunjuk';
        }

        $maxOrder = FormItem::query()
            ->where('form_id', $form->getKey())
            ->where('component', $component)
            ->max('component_order');

        $attrs = [
            'form_id' => $form->getKey(),
            'component' => $component,
            'component_order' => is_null($maxOrder) ? 1 : ((int) $maxOrder + 1),
        ];
        if (self::isLearningDeviceAssessmentForm($form) && $component === 'item') {
            $attrs['max_score'] = 0;
        }

        $formitem = new FormItem($attrs);

        return view('konfigurasi.formitem-action', array_merge(
            [
                'formitem' => $formitem,
                'formContext' => $form,
            ],
            $this->_dataSelection()
        ));
    }

    public function store(Request $request, Form $form)
    {
        $data = $this->validatedPayload($request, $form, null);
        $data['form_id'] = $form->getKey();
        FormItem::create($data);

        return response()->json([
            'success' => true,
            'message' => 'FormItem <strong>'.e($request->input('name')).'</strong> telah ditambahkan',
        ]);
    }

    public function edit(Form $form, FormItem $formItem)
    {
        return view('konfigurasi.formitem-action', array_merge(
            [
                'formitem' => $formItem,
                'formContext' => $form,
            ],
            $this->_dataSelection()
        ));
    }

    public function update(Request $request, Form $form, FormItem $formItem)
    {
        $data = $this->validatedPayload($request, $form, $formItem);
        $data['form_id'] = $form->getKey();
        $formItem->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'FormItem <strong>'.e($request->input('name')).'</strong> telah diperbarui',
        ]);
    }

    public function destroy(Form $form, FormItem $formItem)
    {
        $name = $formItem->name;
        $formItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'FormItem <strong>'.e($name).'</strong> telah dihapus',
        ]);
    }

    /**
     * Simpan ulang nomor urut berdasarkan urutan baris UI (komponen "urutan ke-" per blok component).
     */
    public function reorder(Request $request, Form $form)
    {
        $validated = $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['integer', 'distinct'],
        ]);

        DB::transaction(function () use ($form, $validated): void {
            $ids = array_map('intval', $validated['order']);
            /** @var \Illuminate\Support\Collection<int, FormItem> $items */
            $items = FormItem::query()
                ->where('form_id', $form->getKey())
                ->whereIn('id', $ids)
                ->get()
                ->keyBy('id');

            foreach ($ids as $id) {
                if (!$items->has($id)) {
                    throw ValidationException::withMessages([
                        'order' => ['Ada item tidak valid atau bukan milik form ini.'],
                    ]);
                }
            }

            $perComponent = [];

            foreach ($ids as $id) {
                $item = $items->get($id);
                $c = $item->component ?? '';
                $perComponent[$c] = ($perComponent[$c] ?? 0) + 1;
                $item->component_order = $perComponent[$c];
                $item->save();
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Urutan form item telah disimpan.',
        ]);
    }

    private function _dataSelection(): array
    {
        return [
            'components' => self::CANONICAL_COMPONENTS,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedPayload(Request $request, Form $form, ?FormItem $formItem): array
    {
        $rules = [
            'component' => ['required', 'string', 'max:191'],
            'component_order' => ['nullable', 'integer', 'min:0'],
            'name' => ['required', 'string'],
            'max_score' => ['nullable', 'integer', 'min:0'],
            'form_id' => ['sometimes', 'string'],
        ];

        $data = validator($request->all(), $rules)->validate();

        if (array_key_exists('form_id', $data) && $data['form_id'] !== null && $data['form_id'] !== $form->getKey()) {
            throw ValidationException::withMessages([
                'form_id' => ['Form tidak sesuai.'],
            ]);
        }

        unset($data['form_id']);

        if (self::isLearningDeviceAssessmentForm($form)) {
            if ($formItem !== null) {
                $data['component'] = $formItem->component;
            } elseif (! in_array($data['component'], self::CANONICAL_COMPONENTS, true)) {
                throw ValidationException::withMessages([
                    'component' => ['Bagian tidak valid.'],
                ]);
            }
        }

        return $data;
    }
}
