<?php

namespace App\Http\Controllers\School\Only;

use App\Models\Map;
use App\Models\Form;
use App\Models\FormItem;
use App\Models\Observation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentObservationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:aktivitas/studentobservations/plp-read', ['only' => ['index','show']]);
        $this->middleware('permission:aktivitas/studentobservations/plp-create', ['only' => ['create','store']]);
        $this->middleware('permission:aktivitas/studentobservations/plp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:aktivitas/studentobservations/plp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $forms = Form::whereIn('id',['2024L1','2024L2','2024L3'])->get();
        $map_id = Map::where('year',2025)->firstWhere('student_id',auth()->user()->id)->id;

        return view('aktivitas.only.observation',compact('forms','map_id'));
    }

    public function create($form_id)
    {
        $studentobservation = new Observation();
        return view('aktivitas.only.observation-action', array_merge(
            ['studentobservation'=> $studentobservation],
            $this->_dataSelection($form_id)
            ));
    }

    public function store($form_id, Request $request)
    {
        Observation::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Observation <strong>'.$request->id.'</strong> telah ditambahkan'
        ]);
    }

    public function edit($form_id, Observation $studentobservation)
    {
        return view('aktivitas.only.observation-action', array_merge(
            ['studentobservation'=>$studentobservation],
            $this->_dataSelection($form_id)
            ));
    }

    public function update($form_id, Request $request, Observation $studentobservation)
    {
        $data = $request->all();
        $studentobservation->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Observation <strong>'.$request->id.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(Observation $studentobservation)
    {
        $name = $studentobservation->id;

        $studentobservation->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Observation <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    private function _dataSelection($form_id)
    {
        return [
            'map_id' => Map::where('year',2025)->firstWhere('student_id', auth()->user()->id)->id,
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
