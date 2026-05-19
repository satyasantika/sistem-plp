<?php

/**
 * Penataan grup permission untuk UI modal Pengatur Role (role-permission).
 *
 * Kategori utama diambil dari segmen pertama path permission (mis. konfigurasi/…).
 * Nama kategori bisa dioverride atau diurutkan lewat pengaturan di bawah ini.
 */
return [
    'category_labels' => [
        '_core' => 'Dasar akses aplikasi',
        'dashboard' => 'Dashboard berdasarkan peran',
        'konfigurasi' => 'Konfigurasi sistem',
        'usulan' => 'Usulan sekolah',
        'mapping' => 'Pemetaan',
        'aktivitas' => 'Aktivitas PLP',
        'report' => 'Laporan',
        'data' => 'Data & rekapitulasi',
        'yudisium' => 'Yudisium',
        '_extras' => 'Izin khusus & tidak pola CRUD',
    ],

    /**
     * Angka lebih kecil = lebih atas. Kategori tidak tercantum pakai fallback 900.
     */
    'category_order' => [
        '_core' => 5,
        'dashboard' => 10,
        'konfigurasi' => 20,
        'usulan' => 35,
        'mapping' => 40,
        'aktivitas' => 50,
        'report' => 58,
        'data' => 60,
        'yudisium' => 70,
        '_extras' => 850,
    ],

    /** Label akses CRUD (ikon sudah menggunakan warna konsisten di blade). */
    'action_labels' => [
        'create' => 'Buat',
        'read' => 'Baca',
        'update' => 'Ubah',
        'delete' => 'Hapus',
    ],
];
