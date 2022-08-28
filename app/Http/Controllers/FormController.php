<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use App\DataTables\FormDataTable;

class FormController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:konfigurasi/forms-read', ['only' => ['index','show']]);
        // $this->middleware('permission:konfigurasi/forms-create', ['only' => ['create','store']]);
        // $this->middleware('permission:konfigurasi/forms-update', ['only' => ['edit','update']]);
        // $this->middleware('permission:konfigurasi/forms-delete', ['only' => ['destroy']]);
    }

    public function index(FormDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.form');
    }

    public function create()
    {
        $form = new Form();
        return view('konfigurasi.form-action', array_merge(
            ['form'=> $form],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        Form::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Form <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(Form $form)
    {
        return view('konfigurasi.form-action', array_merge(
            ['form'=>$form],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, Form $form)
    {
        $data = $request->all();
        $form->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Form <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(Form $form)
    {
        $name = $form->name;

        $form->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Form <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    private function _dataSelection()
    {
        return [
            'formtypes' =>  ['yes_no','skor_4','skor_40','skor_max'],
        ];
    }

}
