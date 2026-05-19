@include('konfigurasi.partials.permission-modal-form', [
    'pmAccordionId' => 'pm_role_'.$role->id,
    'pmFormAction' => route('rolepermissions.update', $role->id),
    'pmHiddenNameValue' => $role->name,
    'pmPermissionUi' => $permissionUi,
    'pmCheckedIds' => $rolePermissions,
    'pmInputIdPrefix' => 'role_permission_',
    'pmModalTitle' => 'Kelola hak akses untuk role «' . $role->name . '»',
    'pmSubhead' =>
        'Ijin dikelompokkan menurut modul utama. Gunakan kotak telusuri untuk penyaringan cepat atau tombol «+/- kategori» ketika Anda perlu banyak centang satu area sekaligus.',
    'pmAssignableEmptyNotice' => false,
])
