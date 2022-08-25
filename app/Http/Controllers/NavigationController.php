<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use Illuminate\Http\Request;
use App\DataTables\NavigationDataTable;
use Spatie\Permission\Models\Permission;

class NavigationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/navigations-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/navigations-create', ['only' => ['create','store']]);
        $this->middleware('permission:konfigurasi/navigations-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/navigations-delete', ['only' => ['destroy']]);
    }

    public function index(NavigationDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.navigation');
    }

    public function create()
    {
        return view('konfigurasi.navigation-action',['navigation'=>new Navigation()]);
    }

    public function store(Request $request)
    {
        if ($request->parent_id > 0) {
            Navigation::find($request->parent_id)->children()->create($request->all());
        } else {
            $request->parent_id = null;
            Navigation::create($request->all());
        }
        return response()->json([
            'success' => true,
            'message' => 'Menu <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit(Navigation $navigation)
    {
        return view('konfigurasi.navigation-action', compact('navigation'));
    }

    public function update(Request $request, Navigation $navigation)
    {
        $navigation->name = $request->name;
        $navigation->url = $request->url;
        $navigation->icon = $request->icon;
        $navigation->order = $request->order;
        $navigation->parent_id = $request->parent_id;
        $navigation->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Menu <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(Navigation $navigation)
    {
        $name = $navigation->name;
        $navigation->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Menu <strong>'.$name.'</strong> telah dihapus'
        ]);
    }
}
