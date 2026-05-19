@extends('konfigurasi.datatable')

@push('jscode')
    <script> crudDataTables('roles','role-table') </script>
    @include('partials.datatables.user-role-permission')
    @include('konfigurasi.partials.role-permissions-modal-wire')
    <script> userRolePermission('role-table') </script>
@endpush
