<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\Diary;
use Illuminate\Http\Request;
use App\DataTables\DiaryDataTable;

class DiaryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/diaries-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/diaries-create', ['only' => ['create','store']]);
        $this->middleware('permission:konfigurasi/diaries-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/diaries-delete', ['only' => ['destroy']]);
    }

    public function index(DiaryDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.diary');
    }

    public function create()
    {
        $diary = new Diary();
        return view('konfigurasi.diary-action', array_merge(
            ['diary'=> $diary],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        Diary::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Diary <strong>'.$request->id.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(Diary $diary)
    {
        return view('konfigurasi.diary-action', array_merge(
            ['diary'=>$diary],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, Diary $diary)
    {
        $data = $request->all();
        $diary->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Diary <strong>'.$request->id.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(Diary $diary)
    {
        $name = $diary->id;

        $diary->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Diary <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    private function _dataSelection()
    {
        $day = [];
        for ($i=1; $i <=30; $i++) {
            array_push($day,$i);
        }
        return [
            'maps' =>  Map::all(),
            'day' => $day,
        ];
    }

}
