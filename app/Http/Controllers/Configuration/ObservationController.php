<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Map;
use App\Models\Form;
use App\Models\Observation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\ObservationDataTable;

class ObservationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/observations-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/observations-create', ['only' => ['create','store']]);
        $this->middleware('permission:konfigurasi/observations-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/observations-delete', ['only' => ['destroy']]);
    }

    public function index(ObservationDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.observation');
    }

    public function create()
    {
        $observation = new Observation();
        return view('konfigurasi.observation-action', array_merge(
            ['observation'=> $observation],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        Observation::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Data Observasi telah ditambahkan'
        ]);
    }

    public function edit(Observation $observation)
    {
        return view('konfigurasi.observation-action', array_merge(
            ['observation'=>$observation],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, Observation $observation)
    {
        $data = $request->all();
        $observation->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Observasi telah diperbarui'
        ]);
    }

    public function destroy(Observation $observation)
    {
        $observation->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Observasi telah dihapus'
        ]);
    }

    private function _dataSelection()
    {
        return [
            'maps' =>  Map::where('year',2023)->whereNotNull('student_id')->get(),
            'forms' =>  Form::where('type','yes_no'),
            'options' => ['baik','kurang','tidak']
        ];
    }

}
