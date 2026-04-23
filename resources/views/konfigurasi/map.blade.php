@extends('konfigurasi.datatable')

@push('import')
    <div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
        <a href="{{ route('maps.importpage') }}" class="btn btn-primary btn-sm">
            Import Maps
        </a>
    </div>
@endpush

@push('jscode')
    <script> crudDataTables('maps','map-table') </script>
@endpush
