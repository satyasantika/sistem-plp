<?php

namespace App\Http\Controllers\School;

use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CleaningAssessmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:data/cleaningassessments-read', ['only' => ['index']]);
        $this->middleware('permission:data/cleaningassessments-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $code = Assessment::selectRaw("CONCAT_WS('-',`map_id`,`plp_order`,`form_id`,`form_order`) AS code")
                            ->selectRaw("COUNT(*) AS Total")
                            ->groupBy('code')
                            ->havingRaw('Total > ?',[1])
                            ->pluck('code');
        $assessments = Assessment::whereIn(DB::raw("CONCAT_WS('-',`map_id`,`plp_order`,`form_id`,`form_order`)"),$code)->get();

        return view('cleaning.assessment',compact('assessments'));
    }

    public function destroy(Assessment $cleaningassessment)
    {
        $name = $cleaningassessment->maps->students->name;

        $cleaningassessment->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Duplikasi penilaian terhadap <strong>'.$name.'</strong> telah dihapus'
        ]);
    }


}
