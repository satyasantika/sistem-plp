<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterMapController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:mapping/mastermaps-read', ['only' => ['index','show']]);
        $this->middleware('permission:mapping/mastermaps-create', ['only' => ['create','store']]);
        $this->middleware('permission:mapping/mastermaps-update', ['only' => ['edit','update']]);
        $this->middleware('permission:mapping/mastermaps-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('teams.data.map.master');
    }

    public function create()
    {
        $map = new Map();
        return view('konfigurasi.map-action', array_merge(
            $this->_dataSelection(),
            [
                'map'=> $map,
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
            // 'students' => User::role('mahasiswa')
            //                     ->select('id','name')
            //                     ->orderBy('name')
            //                     ->get(),
            // 'lectures' => User::role('dosen')->select('id','name')->orderBy('name')->get(),
            // 'teachers' => User::role('guru')->select('id','name')->orderBy('name')->get(),
            'schools' =>  School::select('id','name')->orderBy('name')->get(),
        ];
    }

}
