@extends('konfigurasi.datatable')

@push('jscode')
    <script> crudDataTables('roles','role-table') </script>
    <script src="{{ asset('') }}assets/js/user-role-permission-on-datatables.js"></script>
    <script> userRolePermission('role-table') </script>
@endpush
