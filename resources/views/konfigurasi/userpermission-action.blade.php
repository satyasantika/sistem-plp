@php
    $roleHidden = (int) ($userRoleGrantedPermissionCount ?? 0);
@endphp

@include('konfigurasi.partials.permission-modal-form', [
    'pmAccordionId' => 'pm_user_' . $user->id,
    'pmFormAction' => route('userpermissions.update', $user->id),
    'pmHiddenNameValue' => $user->name,
    'pmPermissionUi' => $permissionUi,
    'pmCheckedIds' => $userPermissions,
    'pmInputIdPrefix' => 'user_permission_',
    'pmModalTitle' => 'Hak tambahan langsung untuk «' . e($user->name) . '»',
    'pmSubhead' =>
        'Ini hanya mencakup izin yang belum diperoleh pengguna lewat role apa pun. Perubahan di sini tidak mengubah peran utama pengguna pada menu Role (R).',
    'pmBannerHtml' =>
        $roleHidden > 0
            ? '<div class="alert alert-info mb-0 py-2 px-3 small"><strong>' .
                $roleHidden .
                '</strong> izin disembunyikan karena pengguna telah memilikinya secara otomatis lewat role aktif. ' .
                'Anda dapat menambahkan pengecualian tambahan menggunakan daftar berikut.</div>'
            : null,
    'pmAssignableEmptyNotice' => (($permissionUi['total_checkboxes'] ?? 0) === 0 && $roleHidden > 0),
])
