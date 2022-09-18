<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Navigation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $navigation = new Navigation();
        return view('konfigurasi.navigation-action', array_merge(
            $this->_dataSelection(),
            [
                'navigation'=> $navigation,
            ],
        ));
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

    public function edit(Navigation $navigation)
    {
        return view('konfigurasi.navigation-action', array_merge(
            $this->_dataSelection(),
            [
                'navigation'=> $navigation,
            ],
        ));
    }

    public function update(Request $request, Navigation $navigation)
    {
        $data = $request->all();
        $navigation->fill($data)->save();

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

    private function _dataSelection()
    {

        return [
            'parent_navs' => Navigation::whereNull('parent_id')->get(),
        ];
    }

}
