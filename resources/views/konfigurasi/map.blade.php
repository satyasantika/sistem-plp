@extends('konfigurasi.datatable')

@push('jscode')
    <script>
        crudDataTables('maps','map-table')
    </script>
    {{-- <script type="text/javascript">
    $(function(){
        window.LaravelDataTables=window.LaravelDataTables||{};
        window.LaravelDataTables["map-table"]=$("#map-table").DataTable({
            "serverSide":true,
            "processing":true,
            "ajax":{
                "url":"http:\/\/127.0.0.1:8000\/konfigurasi\/maps",
                "type":"GET",
                "data":function(data) {
                    for (var i = 0, len = data.columns.length; i < len; i++) {
                        if (!data.columns[i].search.value) delete data.columns[i].search;
                        if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                        if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                        if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                    }
                    delete data.search.regex;
                }
            },
            "columns":[
                    {"data":"action","name":"action","title":"","orderable":false,"searchable":false,"width":60,"className":"text-center"},
                    {"data":"sekolah","name":"sekolah","title":"Sekolah","orderable":true,"searchable":true},
                    {"data":"mapel","name":"mapel","title":"Mapel","orderable":true,"searchable":true},
                    {"data":"mahasiswa","name":"mahasiswa","title":"Mahasiswa","orderable":true,"searchable":true},
                    {"data":"dosen","name":"dosen","title":"Dosen","orderable":true,"searchable":true},
                    {"data":"guru","name":"guru","title":"Guru","orderable":true,"searchable":true},
                    {"data":"year","name":"year","title":"Tahun","orderable":true,"searchable":true}
                ]
            });
        });
    </script> --}}
@endpush
