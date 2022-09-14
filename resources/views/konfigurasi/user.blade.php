@extends('konfigurasi.datatable')

@push('jscode')
    <script> crudDataTables('users','user-table') </script>
    <script src="{{ asset('') }}assets/js/user-role-permission-on-datatables.js"></script>
    <script> userRolePermission('user-table') </script>
@endpush
