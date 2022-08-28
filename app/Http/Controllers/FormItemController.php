<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormItem;
use Illuminate\Http\Request;
use App\DataTables\FormItemDataTable;

class FormItemController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/formitems-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/formitems-create', ['only' => ['create','store']]);
        $this->middleware('permission:konfigurasi/formitems-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/formitems-delete', ['only' => ['destroy']]);
    }

    public function index(FormItemDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.formitem');
    }

    public function create()
    {
        $formitem = new FormItem();
        return view('konfigurasi.formitem-action', array_merge(
            ['formitem'=> $formitem],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        FormItem::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'FormItem <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(FormItem $formitem)
    {
        return view('konfigurasi.formitem-action', array_merge(
            ['formitem'=>$formitem],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, FormItem $formitem)
    {
        $data = $request->all();
        $formitem->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'FormItem <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(FormItem $formitem)
    {
        $name = $formitem->name;

        $formitem->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'FormItem <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    private function _dataSelection()
    {
        return [
            'components' =>  ['petunjuk','item','tambahan'],
            'forms' =>  Form::pluck('id')->sort(),
        ];
    }

}
