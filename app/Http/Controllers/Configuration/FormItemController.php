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

    public function __construct()
    {
        $this->middleware('permission:formitems-read', ['only' => ['index']]);
        $this->middleware('permission:formitems-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:formitems-update', ['only' => ['edit', 'update', 'reorder']]);
        $this->middleware('permission:formitems-delete', ['only' => ['destroy']]);
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
            $sectionCreateUrls[$key] = route('forms.items.create', $form).'?component='.$key;
        }

        return view('konfigurasi.form-items', [
            'form' => $form,
            'sectionLabels' => $sectionLabels,
            'groupedRows' => $groupedRows,
            'otherItems' => $otherItems,
            'itemsResourceBase' => url('/konfigurasi/forms/'.$form->getKey().'/items'),
            'formsItemsReorderUrl' => route('forms.items.reorder', $form),
            'enableFormItemsReorder' => auth()->user()?->can('formitems-update') ?? false,
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

        $formitem = new FormItem([
            'form_id' => $form->getKey(),
            'component' => $component,
            'component_order' => is_null($maxOrder) ? 1 : ((int) $maxOrder + 1),
        ]);

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
        $data = $this->validatedPayload($request, $form);
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
        $data = $this->validatedPayload($request, $form);
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
    private function validatedPayload(Request $request, Form $form): array
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

        return $data;
    }
}
