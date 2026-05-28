<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Map;
use App\Models\PlpFinalGradeFormRule;
use App\Models\PlpFinalGradeWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PlpFinalGradeRuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:konfigurasi/plpfinalgraderules-read', ['only' => ['index', 'create']]);
        $this->middleware('permission:konfigurasi/plpfinalgraderules-create', ['only' => ['store']]);
        $this->middleware('permission:konfigurasi/plpfinalgraderules-update', ['only' => ['updateWeights', 'updateTimes']]);
        $this->middleware('permission:konfigurasi/plpfinalgraderules-delete', ['only' => ['destroy', 'destroyWeight']]);
    }

    public function index()
    {
        $activeYear = (int) Map::activeYear();

        $weights = [];
        $weightsJs = [];
        foreach ([0, 1, 2] as $order) {
            $w = PlpFinalGradeWeight::query()
                ->where('year', $activeYear)
                ->where('plp_order', $order)
                ->first();
            if ($w === null) {
                $w = new PlpFinalGradeWeight(array_merge(PlpFinalGradeWeight::defaultsForPlp2(), [
                    'year' => $activeYear,
                    'plp_order' => $order,
                ]));
            }
            $weights[$order] = $w;
            $weightsJs[(string) $order] = [
                'dosen_weight' => number_format((float) $w->dosen_weight, 2, '.', ''),
                'guru_weight' => number_format((float) $w->guru_weight, 2, '.', ''),
            ];
        }

        $rulesQuery = PlpFinalGradeFormRule::query()
            ->where('year', $activeYear)
            ->with('form')
            ->orderBy('form_id');

        $observationPattern = static fn ($q) => $q
            ->where('form_id', 'like', '%L1')
            ->orWhere('form_id', 'like', '%L2')
            ->orWhere('form_id', 'like', '%L3');

        $nonObservationPattern = static fn ($q) => $q
            ->where('form_id', 'not like', '%L1')
            ->where('form_id', 'not like', '%L2')
            ->where('form_id', 'not like', '%L3');

        $rulesMahasiswa = (clone $rulesQuery)
            ->where('assessor', 'mahasiswa')
            ->whereIn('plp_order', [0, 1, 2])
            ->where($observationPattern)
            ->orderBy('plp_order')
            ->orderBy('form_id')
            ->get();

        $rulesDosen = (clone $rulesQuery)
            ->where('assessor', 'dosen')
            ->whereIn('plp_order', [0, 1, 2])
            ->where($nonObservationPattern)
            ->orderBy('plp_order')
            ->orderBy('form_id')
            ->get();

        $rulesGuru = (clone $rulesQuery)
            ->where('assessor', 'guru')
            ->whereIn('plp_order', [0, 1, 2])
            ->where($nonObservationPattern)
            ->orderBy('plp_order')
            ->orderBy('form_id')
            ->get();

        return view('konfigurasi.plp-final-grade-rules', [
            'activeYear' => $activeYear,
            'weights' => $weights,
            'weightsJs' => $weightsJs,
            'rulesMahasiswa' => $rulesMahasiswa,
            'rulesDosen' => $rulesDosen,
            'rulesGuru' => $rulesGuru,
            'createUrlBase' => route('plpfinalgraderules.create'),
        ]);
    }

    public function create(Request $request)
    {
        $activeYear = (int) Map::activeYear();

        $context = $request->query('context', 'dosen');
        if (! in_array($context, ['mahasiswa', 'dosen', 'guru'], true)) {
            $context = 'dosen';
        }

        $readOnly = $request->boolean('view');

        $plpOrder = 1;
        $assessor = 'dosen';

        if ($context === 'mahasiswa') {
            $assessor = 'mahasiswa';
            $plpOrder = $request->integer('plp_order', 0);
            if (! in_array($plpOrder, [0, 1, 2], true)) {
                $plpOrder = 0;
            }
        } elseif ($context === 'dosen') {
            $assessor = 'dosen';
            $plpOrder = $request->integer('plp_order', 1);
            if (! in_array($plpOrder, [0, 1, 2], true)) {
                $plpOrder = 1;
            }
        } elseif ($context === 'guru') {
            $assessor = 'guru';
            $plpOrder = $request->integer('plp_order', 2);
            if (! in_array($plpOrder, [0, 1, 2], true)) {
                $plpOrder = 2;
            }
        }

        $selectedRules = PlpFinalGradeFormRule::query()
            ->where('year', $activeYear)
            ->where('plp_order', $plpOrder)
            ->where('assessor', $assessor)
            ->when($context === 'mahasiswa', fn ($q) => $q->where(
                fn ($sq) => $sq->where('form_id', 'like', '%L1')
                    ->orWhere('form_id', 'like', '%L2')
                    ->orWhere('form_id', 'like', '%L3')
            ))
            ->when($context !== 'mahasiswa', fn ($q) => $q
                ->where('form_id', 'not like', '%L1')
                ->where('form_id', 'not like', '%L2')
                ->where('form_id', 'not like', '%L3')
            )
            ->orderBy('form_id')
            ->get(['form_id', 'times']);

        return view('konfigurasi.plp-final-grade-rule-action', [
            'activeYear' => $activeYear,
            'plpOrder' => $plpOrder,
            'assessor' => $assessor,
            'ruleContext' => $context,
            'readOnly' => $readOnly,
            'selectedFormIds' => $selectedRules->pluck('form_id')->all(),
            'selectedFormTimes' => $selectedRules->pluck('times', 'form_id')->all(),
            'formOptions' => $context === 'mahasiswa'
                ? $this->formOptionsObservation()
                : $this->formOptionsForDropdown(),
        ]);
    }

    public function store(Request $request)
    {
        $activeYear = (int) Map::activeYear();

        $context = $request->input('context', 'dosen');
        if (! in_array($context, ['mahasiswa', 'dosen', 'guru'], true)) {
            $context = 'dosen';
        }
        $isObservation = $context === 'mahasiswa';

        $validated = $request->validate([
            'plp_order' => ['required', 'integer', Rule::in([0, 1, 2])],
            'assessor' => ['required', 'string', Rule::in(['mahasiswa', 'dosen', 'guru'])],
            'form_ids' => ['nullable', 'array'],
            'form_ids.*' => ['string', 'exists:forms,id'],
            'form_times' => ['nullable', 'array'],
            'form_times.*' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $formIds = array_values(array_unique(array_map('strval', $validated['form_ids'] ?? [])));
        $formTimesMap = $validated['form_times'] ?? [];

        DB::transaction(function () use ($activeYear, $validated, $formIds, $formTimesMap, $isObservation): void {
            $deleteQuery = PlpFinalGradeFormRule::query()
                ->where('year', $activeYear)
                ->where('plp_order', $validated['plp_order'])
                ->where('assessor', $validated['assessor']);

            if ($isObservation) {
                $deleteQuery->where(fn ($q) => $q
                    ->where('form_id', 'like', '%L1')
                    ->orWhere('form_id', 'like', '%L2')
                    ->orWhere('form_id', 'like', '%L3')
                );
            } else {
                $deleteQuery
                    ->where('form_id', 'not like', '%L1')
                    ->where('form_id', 'not like', '%L2')
                    ->where('form_id', 'not like', '%L3');
            }

            $deleteQuery->delete();

            foreach ($formIds as $formId) {
                PlpFinalGradeFormRule::query()->create([
                    'year' => $activeYear,
                    'plp_order' => $validated['plp_order'],
                    'assessor' => $validated['assessor'],
                    'form_id' => $formId,
                    'times' => max(1, (int) ($formTimesMap[$formId] ?? 1)),
                ]);
            }
        });

        $count = count($formIds);

        return response()->json([
            'success' => true,
            'message' => $count > 0
                ? 'Daftar form untuk kombinasi PLP & peran ini telah disimpan ('.$count.' form).'
                : 'Semua form untuk kombinasi PLP & peran ini telah dikosongkan.',
        ]);
    }

    public function updateTimes(Request $request, PlpFinalGradeFormRule $plpfinalgraderule)
    {
        $activeYear = (int) Map::activeYear();

        if ((int) $plpfinalgraderule->year !== $activeYear) {
            abort(404);
        }

        $validated = $request->validate([
            'action' => ['required', 'string', Rule::in(['increment', 'decrement'])],
        ]);

        $times = max(1, (int) $plpfinalgraderule->times);

        if ($validated['action'] === 'increment') {
            $times = min(20, $times + 1);
        } else {
            $times = max(1, $times - 1);
        }

        $plpfinalgraderule->update(['times' => $times]);

        return response()->json([
            'success' => true,
            'times' => $times,
            'can_increment' => $times < 20,
            'can_decrement' => $times > 1,
            'message' => 'Jumlah pengisian form '.$plpfinalgraderule->form_id.' diperbarui menjadi '.$times.'×.',
        ]);
    }

    public function destroyWeight(int $plp_order)
    {
        $activeYear = (int) Map::activeYear();

        PlpFinalGradeWeight::query()
            ->where('year', $activeYear)
            ->where('plp_order', $plp_order)
            ->delete();

        $plpLabel = match ($plp_order) {
            0 => 'PLP',
            1 => 'PLP 1',
            default => 'PLP 2',
        };

        return response()->json([
            'success' => true,
            'message' => 'Bobot gabungan '.$plpLabel.' telah dihapus.',
        ]);
    }

    public function destroy(PlpFinalGradeFormRule $plpfinalgraderule)
    {
        $activeYear = (int) Map::activeYear();

        if ((int) $plpfinalgraderule->year !== $activeYear) {
            abort(404);
        }

        $plpfinalgraderule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Form telah dihapus dari sebaran.',
        ]);
    }

    public function updateWeights(Request $request)
    {
        $activeYear = (int) Map::activeYear();

        $validated = $request->validate([
            'plp_order' => ['required', 'integer', Rule::in([0, 1, 2])],
            'dosen_weight' => ['required', 'numeric', 'min:0', 'max:1'],
            'guru_weight' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        $sum = round((float) $validated['dosen_weight'] + (float) $validated['guru_weight'], 4);
        if (abs($sum - 1.0) > 0.005) {
            throw ValidationException::withMessages([
                'guru_weight' => 'Jumlah bobot Dosen + Guru harus bernilai 1 (100%). Nilai kini: '.$sum,
            ]);
        }

        PlpFinalGradeWeight::query()->updateOrCreate(
            [
                'year' => $activeYear,
                'plp_order' => $validated['plp_order'],
            ],
            [
                'dosen_weight' => $validated['dosen_weight'],
                'guru_weight' => $validated['guru_weight'],
            ]
        );

        $plpLabel = match ((int) $validated['plp_order']) {
            0 => 'PLP',
            1 => 'PLP 1',
            default => 'PLP 2',
        };

        return redirect()
            ->route('plpfinalgraderules.index')
            ->with('status', 'Bobot gabungan '.$plpLabel.' telah disimpan untuk tahun akademik '.$activeYear.'.');
    }

    /**
     * @return array<int, array{id: string, name: string}>
     */
    protected function formOptionsForDropdown(): array
    {
        return Form::query()
            ->where('type', '!=', 'yes_no')
            ->where('id', 'not like', '%L1')
            ->where('id', 'not like', '%L2')
            ->where('id', 'not like', '%L3')
            ->orderBy('id')
            ->get(['id', 'name'])
            ->map(fn (Form $f) => ['id' => $f->id, 'name' => $f->name])
            ->all();
    }

    /**
     * Form observasi mahasiswa: kode berakhiran L1, L2, atau L3.
     *
     * @return array<int, array{id: string, name: string}>
     */
    protected function formOptionsObservation(): array
    {
        return Form::query()
            ->where(function ($q): void {
                $q->where('id', 'like', '%L1')
                    ->orWhere('id', 'like', '%L2')
                    ->orWhere('id', 'like', '%L3');
            })
            ->orderBy('id')
            ->get(['id', 'name'])
            ->map(fn (Form $f) => ['id' => $f->id, 'name' => $f->name])
            ->all();
    }
}
