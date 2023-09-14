@extends('konfigurasi.datatable')

@push('import')
    {{-- <button type="button" class="btn btn-primary btn-sm mb-3 btn-import float-end" data-toggle="modal" data-target="#importExcel"> --}}
    <button type="button" class="btn btn-primary btn-sm mb-3 btn-import float-end">
        IMPORT User
    </button>

		<!-- Import Excel -->
		{{-- <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="{{ route('users.import') }}" enctype="multipart/form-data" id="import">
                    @csrf
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
						</div>
						<div class="modal-body">
							<label>Pilih file excel</label>
							<div class="form-group">
								<input type="file" name="file" required="required">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Import</button>
						</div>
					</div>
				</form>
			</div>
		</div> --}}
@endpush

@push('jscode')
    <script> crudDataTables('users','user-table') </script>
    <script src="{{ asset('') }}assets/js/user-role-permission-on-datatables.js"></script>
    <script> userRolePermission('user-table') </script>
    <script src="{{ asset('') }}assets/js/resetpassword-datatables.js"></script>
    <script> updateOnly('user-table') </script>
    <script src="{{ asset('') }}assets/js/activation-datatables.js"></script>
    <script> updateActivation('user-table') </script>
    <script src="{{ asset('') }}assets/js/import-data.js"></script>
    <script> importData('user-table') </script>
@endpush
