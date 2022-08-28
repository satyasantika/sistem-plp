@extends('konfigurasi.datatable')

@push('jscode')
    <script>
        crudDataTables('diaries','diary-table')
    </script>
    {{-- <script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2({
            placeholder: 'Select an option'
        });
    });
    </script> --}}
@endpush
