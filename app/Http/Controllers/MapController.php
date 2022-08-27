<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use App\DataTables\MapDataTable;

class MapController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/maps-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/maps-create', ['only' => ['create','store']]);
        $this->middleware('permission:konfigurasi/maps-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/maps-delete', ['only' => ['destroy']]);
    }

    public function index(MapDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.map');
    }

    public function create()
    {
        $map = new Map();
        return view('konfigurasi.map-action', array_merge(
            $this->_dataSelection(),
            [
                'map'=> $map,
                'students' => User::role('mahasiswa')
                                ->select('id','name')
                                ->whereNotIn('id',Map::pluck('student_id'))
                                ->orderBy('name')
                                ->get(),
            ],
            ));
    }

    public function store(Request $request)
    {
        Map::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Data telah ditambahkan'
        ]);
    }

    public function edit(Map $map)
    {
        return view('konfigurasi.map-action', array_merge(
            ['map'=> $map],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, Map $map)
    {
        $data = $request->all();
        $map->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data telah diperbarui'
        ]);
    }

    public function destroy(Map $map)
    {
        $name = $map->candidate_name;
        $map->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus'
        ]);
    }

    private function _dataSelection()
    {

        return [
            'students' => User::role('mahasiswa')
                                ->select('id','name')
                                ->orderBy('name')
                                ->get(),
            'lectures' => User::role('dosen')->select('id','name')->orderBy('name')->get(),
            'teachers' => User::role('guru')->select('id','name')->orderBy('name')->get(),
            'schools' =>  School::select('id','name')->orderBy('name')->get(),
        ];
    }

}
