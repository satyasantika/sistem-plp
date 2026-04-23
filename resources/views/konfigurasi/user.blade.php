@extends('konfigurasi.datatable')

@push('import')
	<div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
		<a href="{{ route('users.importpage', ['role' => 'dosen']) }}" class="btn btn-primary btn-sm">
			Import Dosen
		</a>
		<a href="{{ route('users.importpage', ['role' => 'guru']) }}" class="btn btn-primary btn-sm">
			Import Guru
		</a>
		<a href="{{ route('users.importpage', ['role' => 'mahasiswa']) }}" class="btn btn-primary btn-sm">
			Import Mahasiswa
		</a>
	</div>

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
@endpush
