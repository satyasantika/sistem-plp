<?php

namespace App\Http\Controllers\Map;

use App\Models\Map;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $schools = Map::select('school_id')->whereNotNull('school_id')->groupBy('school_id')->get();
        $map_data = [];
        foreach ($schools as  $school) {
            $school_count = Map::where('school_id',$school->school_id)->count();
            array_push($map_data,[
                // 'id' => $school->id,
                'school' => Map::find($school->school_id),
                'count' => $school_count,
            ]);
        }
        // dd($map_data);
        return view('teams.data.map.mastermap',compact('map_data'));
    }

    public function create()
    {
        $map = new Map();
        return view('teams.data.map.mastermap-action', array_merge(
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
