<?php

namespace App\Http\Controllers\School;

use App\Models\Map;
use App\Models\Form;
use App\Models\FormItem;
use App\Models\Assessment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssessmentController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:aktivitas/guruassessments-read || aktivitas/dosenassessments-read', ['only' => ['index','show']]);
        // $this->middleware('permission:aktivitas/guruassessments-create || aktivitas/dosenassessments-create', ['only' => ['create','store']]);
        // $this->middleware('permission:aktivitas/guruassessments-update || aktivitas/dosenassessments-update', ['only' => ['edit','update']]);
        // $this->middleware('permission:aktivitas/guruassessments-delete || aktivitas/dosenassessments-delete', ['only' => ['destroy']]);
    }

    public function index($plp_order,$form_id)
    {
        $my_id = auth()->user()->id;
        $maps = Map::where('teacher_id',$my_id)
                ->orWhere('lecture_id',$my_id)
                ->get();

        return view('aktivitas.assessment',compact('plp_order','maps','form_id'));
    }

    public function create($form_id)
    {
        $assessment = new assessment();
        return view('aktivitas.assessment-action', array_merge(
            ['assessment'=> $assessment],
            $this->_dataSelection($form_id)
            ));
    }

    public function store($form_id, Request $request)
    {
        assessment::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'assessment <strong>'.$request->id.'</strong> telah ditambahkan'
        ]);
    }

    public function edit($form_id, assessment $assessment)
    {
        return view('aktivitas.assessment-action', array_merge(
            ['assessment'=>$assessment],
            $this->_dataSelection($form_id)
            ));
    }

    public function update($form_id, Request $request, assessment $assessment)
    {
        $data = $request->all();
        $assessment->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'assessment <strong>'.$request->id.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(assessment $assessment)
    {
        $name = $assessment->id;

        $assessment->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'assessment <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    private function _dataSelection($form_id)
    {
        return [
            'map_id' => Map::firstWhere('student_id', auth()->user()->id)->id,
            'form' => Form::find($form_id),
            'form_guides' => $this->_formByComponent($form_id,'petunjuk'),
            'form_items' => $this->_formByComponent($form_id,'item'),
            'form_extras' => $this->_formByComponent($form_id,'tambahan'),
        ];
    }

    private function _formByComponent($form_id, $component)
    {
        return FormItem::where('form_id',$form_id)->where('component',$component)->orderBy('component_order')->get();
    }

}
