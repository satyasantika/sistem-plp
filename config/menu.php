<?php

return [
    'items' => [
        // ADMIN: konfigurasi inti
        [
            'name' => 'Roles',
            'url' => 'konfigurasi/roles',
            'permission' => 'roles-read',
            'icon' => 'ti-shield'
        ],
        [
            'name' => 'Permissions',
            'url' => 'konfigurasi/permissions',
            'permission' => 'permissions-read',
            'icon' => 'ti-key'
        ],
        [
            'name' => 'Users',
            'url' => 'konfigurasi/users',
            'permission' => 'users-read',
            'icon' => 'ti-user'
        ],
        [
            'name' => 'Schools',
            'url' => 'konfigurasi/schools',
            'permission' => 'schools-read',
            'icon' => 'ti-home'
        ],
        [
            'name' => 'School User Proposals',
            'url' => 'konfigurasi/schooluserproposals',
            'permission' => 'schooluserproposals-read',
            'icon' => 'ti-clipboard'
        ],
        [
            'name' => 'Maps',
            'url' => 'konfigurasi/maps',
            'permission' => 'maps-read',
            'icon' => 'ti-map-alt'
        ],
        [
            'name' => 'Forms',
            'url' => 'konfigurasi/forms',
            'permission' => 'forms-read',
            'icon' => 'ti-layout-list-thumb'
        ],
        [
            'name' => 'Form Items',
            'url' => 'konfigurasi/formitems',
            'permission' => 'formitems-read',
            'icon' => 'ti-list'
        ],
        [
            'name' => 'Assessments',
            'url' => 'konfigurasi/assessments',
            'permission' => 'assessments-read',
            'icon' => 'ti-check-box'
        ],
        [
            'name' => 'Observations',
            'url' => 'konfigurasi/observations',
            'permission' => 'observations-read',
            'icon' => 'ti-eye'
        ],

        // KEPSEK/KORGURU: usulan sekolah
        [
            'name' => 'School Coordinators',
            'url' => 'usulan/schoolcoordinators',
            'permission' => 'usulan/schoolcoordinators-read',
            'icon' => 'ti-pencil-alt'
        ],
        [
            'name' => 'School Teachers',
            'url' => 'usulan/schoolteachers',
            'permission' => 'usulan/schoolteachers-read',
            'icon' => 'ti-write'
        ],

        // MAPPING: admin/kajur/kepsek/korguru
        [
            'name' => 'Master Maps',
            'url' => 'mapping/mastermaps',
            'permission' => 'mapping/mastermaps-read',
            'icon' => 'ti-world'
        ],
        [
            'name' => 'Departement Maps',
            'url' => 'mapping/departementmaps',
            'permission' => 'mapping/departementmaps-read',
            'icon' => 'ti-layers'
        ],
        [
            'name' => 'Teacher Maps',
            'url' => 'mapping/teachermaps',
            'permission' => 'mapping/teachermaps-read',
            'icon' => 'ti-location-pin'
        ],

        // DOSEN/GURU/MAHASISWA: aktivitas PLP 1-2
        [
            'name' => 'Student Diaries PLP 1',
            'url' => 'aktivitas/studentdiaries/plp1',
            'permission' => 'aktivitas/studentdiaries/plp1-read',
            'icon' => 'ti-agenda'
        ],
        [
            'name' => 'Student Diaries PLP 2',
            'url' => 'aktivitas/studentdiaries/plp2',
            'permission' => 'aktivitas/studentdiaries/plp2-read',
            'icon' => 'ti-agenda'
        ],
        [
            'name' => 'Diary Verifications PLP 1',
            'url' => 'aktivitas/diaryverifications/plp1',
            'permission' => 'aktivitas/diaryverifications/plp1-read',
            'icon' => 'ti-check'
        ],
        [
            'name' => 'Diary Verifications PLP 2',
            'url' => 'aktivitas/diaryverifications/plp2',
            'permission' => 'aktivitas/diaryverifications/plp2-read',
            'icon' => 'ti-check'
        ],
        [
            'name' => 'Rekap Nilai PLP 1',
            'url' => 'aktivitas/schoolassessments/plp1',
            'permission' => 'aktivitas/schoolassessments/plp1-read',
            'icon' => 'ti-medall'
        ],
        [
            'name' => 'Rekap Nilai PLP 2',
            'url' => 'aktivitas/schoolassessments/plp2',
            'permission' => 'aktivitas/schoolassessments/plp2-read',
            'icon' => 'ti-cup'
        ],
        [
            'name' => 'Nilai N1',
            'url' => 'aktivitas/schoolassessments/plp2/2022N1',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N1-read',
            'icon' => 'ti-star'
        ],
        [
            'name' => 'Nilai N2.1',
            'url' => 'aktivitas/schoolassessments/plp1/2022N2',
            'permission' => 'aktivitas/schoolassessments/plp1/2022N2-read',
            'icon' => 'ti-star'
        ],
        [
            'name' => 'Nilai N2.2',
            'url' => 'aktivitas/schoolassessments/plp2/2022N2',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N2-read',
            'icon' => 'ti-star'
        ],
        [
            'name' => 'Nilai N3',
            'url' => 'aktivitas/schoolassessments/plp2/2022N3',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N3-read',
            'icon' => 'ti-star'
        ],
        [
            'name' => 'Nilai N4',
            'url' => 'aktivitas/schoolassessments/plp2/2022N4',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N4-read',
            'icon' => 'ti-star'
        ],
        [
            'name' => 'Nilai N5',
            'url' => 'aktivitas/schoolassessments/plp2/2022N5',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N5-read',
            'icon' => 'ti-star'
        ],
        [
            'name' => 'Nilai N6',
            'url' => 'aktivitas/schoolassessments/plp2/2022N6',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N6-read',
            'icon' => 'ti-star'
        ],
        [
            'name' => 'Nilai N7',
            'url' => 'aktivitas/schoolassessments/plp2/2022N7',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N7-read',
            'icon' => 'ti-star'
        ],
        [
            'name' => 'Nilai N8',
            'url' => 'aktivitas/schoolassessments/plp1/2022N8',
            'permission' => 'aktivitas/schoolassessments/plp1/2022N8-read',
            'icon' => 'ti-star'
        ],
        [
            'name' => 'Student Diaries',
            'url' => 'aktivitas/studentdiaries/plp',
            'permission' => 'aktivitas/studentdiaries/plp-read',
            'icon' => 'ti-agenda'
        ],
        [
            'name' => 'Diary Verifications',
            'url' => 'aktivitas/diaryverifications/plp',
            'permission' => 'aktivitas/diaryverifications/plp-read',
            'icon' => 'ti-check'
        ],
        [
            'name' => 'Student Observations',
            'url' => 'aktivitas/studentobservations/plp',
            'permission' => 'aktivitas/studentobservations/plp-read',
            'icon' => 'ti-search'
        ],
        [
            'name' => 'School Assessments',
            'url' => 'aktivitas/schoolassessments/plp',
            'permission' => 'aktivitas/schoolassessments/plp-read',
            'icon' => 'ti-bar-chart'
        ],
        [
            'name' => 'Teaching Responsibility',
            'url' => 'aktivitas/teachingrespons',
            'permission' => 'aktivitas/teachingrespons-read',
            'icon' => 'ti-blackboard'
        ],

        // LAPORAN UMUM
        [
            'name' => 'Mapping Result',
            'url' => 'report/mappingresult',
            'permission' => 'report/mappingresult-read',
            'icon' => 'ti-pie-chart'
        ],
        [
            'name' => 'Mapping Quota',
            'url' => 'report/mappingquota',
            'permission' => 'report/mappingquota-read',
            'icon' => 'ti-stats-up'
        ],
        [
            'name' => 'Summary',
            'url' => 'report/summary',
            'permission' => 'report/summary-read',
            'icon' => 'ti-book'
        ],
        [
            'name' => 'School User Proposal Report',
            'url' => 'report/schooluserproposal',
            'permission' => 'report/schooluserproposal-read',
            'icon' => 'ti-files'
        ],
        [
            'name' => 'Profile Progress',
            'url' => 'data/progress/profile',
            'permission' => 'data/progress/profile-read',
            'icon' => 'ti-id-badge'
        ],

        // KAJUR: yudisium/progress PLP per periode
        [
            'name' => 'Yudisium PLP',
            'url' => 'yudisium/plp',
            'permissions_any' => ['yudisium/plp-read', 'yudisium/plp1-read', 'yudisium/plp2-read'],
            'icon' => 'ti-medall'
        ],
        [
            'name' => 'Progress PLP',
            'url' => 'data/progress/plp',
            'permissions_any' => ['data/progress/plp-read', 'data/progress/plp1-read', 'data/progress/plp2-read'],
            'icon' => 'ti-dashboard'
        ],

        // MODE ONLY: endpoint tanpa suffix plp1/plp2
        [
            'name' => 'Summary PLP',
            'url' => 'report/summary/plp',
            'permission' => 'report/summary/plp-read',
            'icon' => 'ti-book'
        ],
    ],
];
