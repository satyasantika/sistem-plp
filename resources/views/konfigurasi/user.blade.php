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
	<script>
		(function () {
			const tableSelector = '#user-table'
			const modalSelector = '#modalAction'
			const modalDialogSelector = '#modalAction .modal-dialog'

			const ensureModalInBody = () => {
				const modalEl = document.querySelector(modalSelector)

				if (!modalEl || modalEl.parentElement === document.body) {
					return
				}

				document.body.appendChild(modalEl)
			}

			const getModal = () => {
				const modalElement = document.getElementById('modalAction')

				if (!modalElement || !window.bootstrap || !window.bootstrap.Modal) {
					return null
				}

				return window.bootstrap.Modal.getOrCreateInstance(modalElement)
			}

			const openModal = () => {
				const modal = getModal()
				const $modal = $(modalSelector)

				const forceVisible = () => {
					$modal.css({
						display: 'block',
						position: 'fixed',
						inset: '0',
						zIndex: '20000',
						overflowX: 'hidden',
						overflowY: 'auto',
						background: 'rgba(0,0,0,0.45)'
					})
					.addClass('show')
					.attr('aria-modal', 'true')
					.removeAttr('aria-hidden')

					$(`${modalSelector} .modal-dialog`).css({
						pointerEvents: 'auto',
						margin: '1.75rem auto',
						zIndex: '20001'
					})

					$('body').addClass('modal-open')
				}

				if (modal) {
					modal.show()
					forceVisible()
					return true
				}

				if ($.fn.modal && $(modalSelector).length) {
					$(modalSelector).modal('show')
					forceVisible()
					return true
				}

				if ($modal.length) {
					forceVisible()
					return true
				}

				return false
			}

			const closeModal = () => {
				const modal = getModal()
				const $modal = $(modalSelector)

				const resetForcedStyle = () => {
					$modal.removeAttr('style')
						.removeClass('show')
						.attr('aria-hidden', 'true')
						.removeAttr('aria-modal')

					$(`${modalSelector} .modal-dialog`).removeAttr('style')
					$('body').removeClass('modal-open')
					$('.modal-backdrop').remove()
				}

				if (modal) {
					modal.hide()
					resetForcedStyle()
					return
				}

				if ($.fn.modal && $(modalSelector).length) {
					$(modalSelector).modal('hide')
					resetForcedStyle()
					return
				}

				resetForcedStyle()
			}

			const reloadTable = () => {
				if (window.LaravelDataTables?.['user-table']) {
					window.LaravelDataTables['user-table'].ajax.reload(null, false)
					return
				}

				if ($.fn.DataTable.isDataTable(tableSelector)) {
					$(tableSelector).DataTable().ajax.reload(null, false)
				}
			}

			const showAjaxError = (response, fallbackMessage) => {
				iziToast.error({
					title: 'Gagal',
					message: response.responseJSON?.message || fallbackMessage,
					position: 'topRight'
				})
			}

			const bindFormAction = () => {
				$(document)
					.off('submit.userFormAction', '#formAction')
					.on('submit.userFormAction', '#formAction', function (e) {
						e.preventDefault()

						const formData = new FormData(this)
						const actionUrl = this.getAttribute('action')

						$.ajax({
							method: 'POST',
							url: actionUrl,
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							data: formData,
							processData: false,
							contentType: false,
							success: function (response) {
								reloadTable()
								closeModal()
								iziToast.success({
									title: 'Saved!',
									message: response.message,
									position: 'topRight'
								})
							},
							error: function (response) {
								let errors = response.responseJSON?.errors

								if (errors) {
									for (const [key, value] of Object.entries(errors)) {
										$(`[name='${key}']`).parent().append(`<span class='text-danger text-small'>${value}</span>`)
									}
								}

								showAjaxError(response, 'Data gagal disimpan.')
							}
						})
					})
			}

			const loadModalContent = (url, fallbackMessage) => {
				$.ajax({
					method: 'GET',
					url,
					success: function (response) {
						ensureModalInBody()
						$(modalDialogSelector).html(response)

						if (!openModal()) {
							iziToast.error({
								title: 'Gagal',
								message: 'Modal API tidak tersedia.',
								position: 'topRight'
							})
							return
						}

						bindFormAction()
					},
					error: function (response) {
						showAjaxError(response, fallbackMessage)
					}
				})
			}

			$(document)
				.off('click.userAdd', '.btn-add')
				.on('click.userAdd', '.btn-add', function () {
					loadModalContent('/konfigurasi/users/create', 'Form tambah user gagal dimuat.')
				})

			$(document)
				.off('click.userAction', `${tableSelector} .action`)
				.on('click.userAction', `${tableSelector} .action`, function () {
					const data = $(this).data()
					const id = data.id
					const jenis = data.jenis

					if (jenis === 'delete') {
						Swal.fire({
							title: 'Hapus permanen?',
							text: 'Data sepenuhnya akan terhapus dari sistem!',
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, delete it!'
						}).then((result) => {
							if (!result.isConfirmed) {
								return
							}

							$.ajax({
								method: 'DELETE',
								url: `/konfigurasi/users/${id}`,
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								},
								success: function (response) {
									reloadTable()
									iziToast.warning({
										title: 'Deleted!',
										message: response.message,
										position: 'topRight'
									})
								},
								error: function (response) {
									showAjaxError(response, 'Data gagal dihapus.')
								}
							})
						})

						return
					}

					loadModalContent(`/konfigurasi/users/${id}/edit`, 'Form edit user gagal dimuat.')
				})

			$(document)
				.off('click.userRolePermission', `${tableSelector} .rolepermission-action`)
				.on('click.userRolePermission', `${tableSelector} .rolepermission-action`, function () {
					const data = $(this).data()
					const id = data.id
					const jenis = data.jenis

					if (jenis === 'rolepermission') {
						loadModalContent(`/konfigurasi/rolepermissions/${id}/edit`, 'Form role permission gagal dimuat.')
						return
					}

					if (jenis === 'userpermission') {
						loadModalContent(`/konfigurasi/userpermissions/${id}/edit`, 'Form user permission gagal dimuat.')
						return
					}

					loadModalContent(`/konfigurasi/userroles/${id}/edit`, 'Form user role gagal dimuat.')
				})

			$(document)
				.off('click.userModalDismiss', `${modalSelector} [data-bs-dismiss="modal"]`)
				.on('click.userModalDismiss', `${modalSelector} [data-bs-dismiss="modal"]`, function (e) {
					e.preventDefault()
					closeModal()
				})

			$(document)
				.off('click.userModalBackdrop', modalSelector)
				.on('click.userModalBackdrop', modalSelector, function (e) {
					if (e.target === this) {
						closeModal()
					}
				})

			ensureModalInBody()
		})()
	</script>
	<script src="{{ asset('') }}assets/js/resetpassword-datatables.js?v={{ filemtime(public_path('assets/js/resetpassword-datatables.js')) }}"></script>
    <script> updateOnly('user-table') </script>
	<script src="{{ asset('') }}assets/js/activation-datatables.js?v={{ filemtime(public_path('assets/js/activation-datatables.js')) }}"></script>
    <script> updateActivation('user-table') </script>
	<script>
		(function () {
			const modalSelector = '#modalAction'
			const dialogSelector = '#modalAction .modal-dialog'
			const tableSelector = '#user-table'

			const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

			const toastSuccess = (message) => {
				if (window.iziToast) {
					window.iziToast.success({ title: 'Saved!', message, position: 'topRight' })
					return
				}

				console.log(message)
			}

			const toastError = (message) => {
				if (window.iziToast) {
					window.iziToast.error({ title: 'Gagal', message, position: 'topRight' })
					return
				}

				console.error(message)
			}

			const ensureModalInBody = () => {
				const modal = document.querySelector(modalSelector)

				if (!modal || modal.parentElement === document.body) {
					return modal
				}

				document.body.appendChild(modal)
				return modal
			}

			const openModal = () => {
				const modal = ensureModalInBody()
				if (!modal) {
					return
				}

				modal.classList.add('show')
				modal.style.display = 'block'
				modal.style.position = 'fixed'
				modal.style.inset = '0'
				modal.style.zIndex = '20000'
				modal.style.overflowX = 'hidden'
				modal.style.overflowY = 'auto'
				modal.style.background = 'rgba(0,0,0,0.45)'
				modal.setAttribute('aria-modal', 'true')
				modal.removeAttribute('aria-hidden')

				const dialog = document.querySelector(dialogSelector)
				if (dialog) {
					dialog.style.pointerEvents = 'auto'
					dialog.style.margin = '1.75rem auto'
					dialog.style.zIndex = '20001'
				}

				document.body.classList.add('modal-open')
			}

			const closeModal = () => {
				const modal = document.querySelector(modalSelector)
				if (!modal) {
					return
				}

				modal.classList.remove('show')
				modal.style.display = 'none'
				modal.removeAttribute('style')
				modal.setAttribute('aria-hidden', 'true')
				modal.removeAttribute('aria-modal')

				const dialog = document.querySelector(dialogSelector)
				if (dialog) {
					dialog.removeAttribute('style')
				}

				document.body.classList.remove('modal-open')
				document.querySelectorAll('.modal-backdrop').forEach((el) => el.remove())
			}

			const reloadTable = () => {
				if (window.LaravelDataTables && window.LaravelDataTables['user-table']) {
					window.LaravelDataTables['user-table'].ajax.reload(null, false)
					return
				}

				if (window.jQuery && window.jQuery.fn && window.jQuery.fn.DataTable && window.jQuery.fn.DataTable.isDataTable(tableSelector)) {
					window.jQuery(tableSelector).DataTable().ajax.reload(null, false)
				}
			}

			const loadModal = async (url, fallbackMessage) => {
				try {
					const response = await fetch(url, {
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						}
					})

					if (!response.ok) {
						throw new Error(fallbackMessage)
					}

					const html = await response.text()
					const dialog = document.querySelector(dialogSelector)
					if (!dialog) {
						throw new Error('Modal container tidak ditemukan.')
					}

					dialog.innerHTML = html
					openModal()
				} catch (error) {
					toastError(error.message || fallbackMessage)
				}
			}

			const submitFormAction = async (form) => {
				const formData = new FormData(form)
				const actionUrl = form.getAttribute('action')
				const csrf = getCsrf()

				try {
					const response = await fetch(actionUrl, {
						method: 'POST',
						headers: {
							'X-CSRF-TOKEN': csrf,
							'X-Requested-With': 'XMLHttpRequest'
						},
						body: formData
					})

					const data = await response.json().catch(() => ({}))

					if (!response.ok) {
						if (data.errors) {
							Object.keys(data.errors).forEach((key) => {
								const input = form.querySelector(`[name="${key}"]`)
								if (!input || !input.parentElement) {
									return
								}

								const span = document.createElement('span')
								span.className = 'text-danger text-small'
								span.textContent = data.errors[key]
								input.parentElement.appendChild(span)
							})
						}

						throw new Error(data.message || 'Data gagal disimpan.')
					}

					reloadTable()
					closeModal()
					toastSuccess(data.message || 'Data berhasil disimpan.')
				} catch (error) {
					toastError(error.message || 'Data gagal disimpan.')
				}
			}

			document.addEventListener('click', function (event) {
				const dismissButton = event.target.closest(`${modalSelector} [data-bs-dismiss="modal"]`)
				if (dismissButton) {
					event.preventDefault()
					event.stopPropagation()
					closeModal()
					return
				}

				const modalRoot = event.target.closest(modalSelector)
				if (modalRoot && event.target === modalRoot) {
					event.preventDefault()
					closeModal()
					return
				}

				const addButton = event.target.closest('.btn-add')
				if (addButton) {
					event.preventDefault()
					event.stopPropagation()
					loadModal('/konfigurasi/users/create', 'Form tambah user gagal dimuat.')
					return
				}

				const rolePermissionButton = event.target.closest(`${tableSelector} .rolepermission-action`)
				if (rolePermissionButton) {
					event.preventDefault()
					event.stopPropagation()
					const id = rolePermissionButton.getAttribute('data-id')
					const jenis = rolePermissionButton.getAttribute('data-jenis')

					if (jenis === 'rolepermission') {
						loadModal(`/konfigurasi/rolepermissions/${id}/edit`, 'Form role permission gagal dimuat.')
						return
					}

					if (jenis === 'userpermission') {
						loadModal(`/konfigurasi/userpermissions/${id}/edit`, 'Form user permission gagal dimuat.')
						return
					}

					loadModal(`/konfigurasi/userroles/${id}/edit`, 'Form user role gagal dimuat.')
					return
				}

				const actionButton = event.target.closest(`${tableSelector} .action`)
				if (actionButton) {
					event.preventDefault()
					event.stopPropagation()
					const id = actionButton.getAttribute('data-id')
					const jenis = actionButton.getAttribute('data-jenis')

					if (jenis === 'delete') {
						const confirmDelete = window.Swal
							? window.Swal.fire({
								title: 'Hapus permanen?',
								text: 'Data sepenuhnya akan terhapus dari sistem!',
								icon: 'warning',
								showCancelButton: true,
								confirmButtonText: 'Yes, delete it!'
							}).then((result) => result.isConfirmed)
							: Promise.resolve(window.confirm('Hapus permanen data ini?'))

						confirmDelete.then(async (isConfirmed) => {
							if (!isConfirmed) {
								return
							}

							try {
								const response = await fetch(`/konfigurasi/users/${id}`, {
									method: 'DELETE',
									headers: {
										'X-CSRF-TOKEN': getCsrf(),
										'X-Requested-With': 'XMLHttpRequest'
									}
								})

								const data = await response.json().catch(() => ({}))
								if (!response.ok) {
									throw new Error(data.message || 'Data gagal dihapus.')
								}

								reloadTable()
								if (window.iziToast) {
									window.iziToast.warning({ title: 'Deleted!', message: data.message || 'Data berhasil dihapus.', position: 'topRight' })
								}
							} catch (error) {
								toastError(error.message || 'Data gagal dihapus.')
							}
						})

						return
					}

					loadModal(`/konfigurasi/users/${id}/edit`, 'Form edit user gagal dimuat.')
				}
			}, true)

			document.addEventListener('submit', function (event) {
				const form = event.target.closest('#formAction')
				if (!form) {
					return
				}

				event.preventDefault()
				submitFormAction(form)
			})

			ensureModalInBody()
		})()
	</script>
@endpush
