<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Map;
use App\Models\Form;
use App\Models\Assessment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\GuardsAssessmentDuplicates;
use App\DataTables\AssessmentDataTable;
use App\Services\MapFinalGradeCalculator;

class AssessmentController extends Controller
{
    use GuardsAssessmentDuplicates;

    public function __construct(private MapFinalGradeCalculator $gradeCalculator)
    {
        $this->middleware('permission:assessments-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:assessments-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:assessments-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:assessments-delete', ['only' => ['destroy']]);
    }

    public function index(AssessmentDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.assessment');
    }

    public function create()
    {
        $assessment = new Assessment();
        return view('konfigurasi.assessment-action', array_merge(
            ['assessment' => $assessment],
            $this->_dataSelection()
        ));
    }

    public function store(Request $request)
    {
        $request->merge(['assessor' => $this->normalizedAssessor($request)]);
        if ($response = $this->assertAssessmentSlotAvailable($request)) {
            return $response;
        }

        $data = $request->merge([
            'grade' => $request->score1
                + $request->score2
                + $request->score3
                + $request->score4
                + $request->score5
                + $request->score6
                + $request->score7
                + $request->score8
                + $request->score9
            ,
        ]);
        Assessment::create($data->all());
        $map_id = (int) $request->map_id;
        $this->refreshMapGrades($map_id, (int) $request->plp_order);

        return response()->json([
            'success' => true,
            'message' => 'Data Penilaian telah ditambahkan'
        ]);
    }

    public function edit(Assessment $assessment)
    {
        return view('konfigurasi.assessment-action', array_merge(
            ['assessment' => $assessment],
            $this->_dataSelection()
        ));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $request->merge(['assessor' => $this->normalizedAssessor($request)]);
        if ($response = $this->assertAssessmentSlotAvailable($request, $assessment)) {
            return $response;
        }

        $data = $request->all();
        $data['grade'] = $request->score1
            + $request->score2
            + $request->score3
            + $request->score4
            + $request->score5
            + $request->score6
            + $request->score7
            + $request->score8
            + $request->score9
        ;

        $assessment->fill($data)->save();
        $map_id = (int) $assessment->map_id;
        $this->refreshMapGrades($map_id, (int) $assessment->plp_order);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Penilaian telah diperbarui'
        ]);
    }

    public function destroy(Assessment $assessment)
    {
        $mapId = (int) $assessment->map_id;
        $plpOrder = (int) $assessment->plp_order;
        $assessment->delete();
        $this->refreshMapGrades($mapId, $plpOrder);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Penilaian telah dihapus'
        ]);
    }

    private function refreshMapGrades(int $mapId, int $plpOrder): void
    {
        $this->gradeCalculator->recalculateMapForPlp($mapId, $plpOrder);
        $this->gradeCalculator->recalculateCombinedDisplay($mapId);
    }

    private function _dataSelection()
    {
        return [
            'maps' => Map::where('year', 2023)->whereNotNull('student_id')->get(),
            'forms' => Form::whereNot('type', 'yes_no'),
            'form_order' => [1, 2, 3, 4, 5, 6],
            'items' => ['score1', 'score2', 'score3', 'score4', 'score5', 'score6', 'score7', 'score8', 'score9'],
        ];
    }

}
