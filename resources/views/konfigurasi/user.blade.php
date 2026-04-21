@extends('konfigurasi.datatable')

@push('import')
	<div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
		<button type="button" class="btn btn-primary btn-sm btn-import" data-role="dosen" data-title="Import Dosen">
			Import Dosen
		</button>
		<button type="button" class="btn btn-primary btn-sm btn-import" data-role="guru" data-title="Import Guru">
			Import Guru
		</button>
		<button type="button" class="btn btn-primary btn-sm btn-import" data-role="mahasiswa" data-title="Import Mahasiswa">
			Import Mahasiswa
		</button>
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
    <script src="{{ asset('') }}assets/js/import-data.js"></script>
    <script> importData('user-table') </script>
@endpush
