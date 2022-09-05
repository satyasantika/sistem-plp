<?php

namespace App\Http\Controllers\School;

use App\Models\Map;
use App\Models\Observation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentObservationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:aktivitas/studentobservations-read', ['only' => ['index','show']]);
        $this->middleware('permission:aktivitas/studentobservations-create', ['only' => ['create','store']]);
        $this->middleware('permission:aktivitas/studentobservations-update', ['only' => ['edit','update']]);
        $this->middleware('permission:aktivitas/studentobservations-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $id = auth()->user()->id;
        $myMapId = Map::firstWhere('student_id',$id)->id;
        $observations = Observation::where('map_id',$myMapId)->where('plp_order',1)->orderBy('day_order')->get();

        return view('aktivitas.logbook',compact('observations'));
    }

    public function create()
    {
        $studentobservation = new Observation();
        return view('aktivitas.logbook-action', array_merge(
            ['studentobservation'=> $studentobservation],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        Observation::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Observation <strong>'.$request->id.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(Observation $studentobservation)
    {
        return view('aktivitas.logbook-action', array_merge(
            ['studentobservation'=>$studentobservation],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, Observation $studentobservation)
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

    private function _dataSelection()
    {
        $days = [];
        for ($i=1; $i <=30; $i++) {
            array_push($days,$i);
        }

        return [
            'days' => $days,
            'myMapId' => Map::firstWhere('student_id', auth()->user()->id)->id,
        ];
    }

}
