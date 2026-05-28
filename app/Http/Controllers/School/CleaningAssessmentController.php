<?php

namespace App\Http\Controllers\School;

use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\MapFinalGradeCalculator;

class CleaningAssessmentController extends Controller
{
    public function __construct(private MapFinalGradeCalculator $gradeCalculator)
    {
        $this->middleware('permission:data/cleaningassessments-read', ['only' => ['index']]);
        $this->middleware('permission:data/cleaningassessments-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $code = Assessment::selectRaw("CONCAT_WS('-',`map_id`,`plp_order`,`form_id`,`form_order`,`assessor`) AS code")
                            ->selectRaw("COUNT(*) AS Total")
                            ->groupBy('code')
                            ->havingRaw('Total > ?',[1])
                            ->pluck('code');
        $assessments = Assessment::whereIn(DB::raw("CONCAT_WS('-',`map_id`,`plp_order`,`form_id`,`form_order`,`assessor`)"),$code)->get();

        return view('cleaning.assessment',compact('assessments'));
    }

    public function destroy(Assessment $cleaningassessment)
    {
        $name = $cleaningassessment->maps->students->name;
        $mapId = (int) $cleaningassessment->map_id;
        $plpOrder = (int) $cleaningassessment->plp_order;

        $cleaningassessment->delete();
        $this->gradeCalculator->recalculateMapForPlp($mapId, $plpOrder);
        $this->gradeCalculator->recalculateCombinedDisplay($mapId);

        return response()->json([
            'status' => 'success',
            'message' => 'Duplikasi penilaian terhadap <strong>'.$name.'</strong> telah dihapus'
        ]);
    }


}
