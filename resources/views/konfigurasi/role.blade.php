@extends('konfigurasi.datatable')

@push('jscode')
    <script> crudDataTables('roles','role-table') </script>
    @include('partials.datatables.user-role-permission')
    <script> userRolePermission('role-table') </script>
@endpush
