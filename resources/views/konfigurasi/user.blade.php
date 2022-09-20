@extends('konfigurasi.datatable')

@push('jscode')
    <script> crudDataTables('users','user-table') </script>
    <script src="{{ asset('') }}assets/js/user-role-permission-on-datatables.js"></script>
    <script> userRolePermission('user-table') </script>
    <script src="{{ asset('') }}assets/js/resetpassword-datatables.js"></script>
    <script> updateOnly('user-table') </script>
@endpush
