<?php

return [
    'items' => [
        // ADMIN: konfigurasi inti
        [
            'name' => 'Roles',
            'url' => 'konfigurasi/roles',
            'permission' => 'roles-read'
        ],
        [
            'name' => 'Permissions',
            'url' => 'konfigurasi/permissions',
            'permission' => 'permissions-read'
        ],
        [
            'name' => 'Users',
            'url' => 'konfigurasi/users',
            'permission' => 'users-read'
        ],
        [
            'name' => 'Schools',
            'url' => 'konfigurasi/schools',
            'permission' => 'schools-read'
        ],
        [
            'name' => 'School User Proposals',
            'url' => 'konfigurasi/schooluserproposals',
            'permission' => 'schooluserproposals-read'
        ],
        [
            'name' => 'Maps',
            'url' => 'konfigurasi/maps',
            'permission' => 'maps-read'
        ],
        [
            'name' => 'Forms',
            'url' => 'konfigurasi/forms',
            'permission' => 'forms-read'
        ],
        [
            'name' => 'Form Items',
            'url' => 'konfigurasi/formitems',
            'permission' => 'formitems-read'
        ],
        [
            'name' => 'Assessments',
            'url' => 'konfigurasi/assessments',
            'permission' => 'assessments-read'
        ],
        [
            'name' => 'Observations',
            'url' => 'konfigurasi/observations',
            'permission' => 'observations-read'
        ],

        // KEPSEK/KORGURU: usulan sekolah
        [
            'name' => 'School Coordinators',
            'url' => 'usulan/schoolcoordinators',
            'permission' => 'usulan/schoolcoordinators-read'
        ],
        [
            'name' => 'School Teachers',
            'url' => 'usulan/schoolteachers',
            'permission' => 'usulan/schoolteachers-read'
        ],

        // MAPPING: admin/kajur/kepsek/korguru
        [
            'name' => 'Master Maps',
            'url' => 'mapping/mastermaps',
            'permission' => 'mapping/mastermaps-read'
        ],
        [
            'name' => 'Departement Maps',
            'url' => 'mapping/departementmaps',
            'permission' => 'mapping/departementmaps-read'
        ],
        [
            'name' => 'Teacher Maps',
            'url' => 'mapping/teachermaps',
            'permission' => 'mapping/teachermaps-read'
        ],

        // DOSEN/GURU/MAHASISWA: aktivitas PLP 1-2
        [
            'name' => 'Student Diaries PLP 1',
            'url' => 'aktivitas/studentdiaries/plp1',
            'permission' => 'aktivitas/studentdiaries/plp1-read'
        ],
        [
            'name' => 'Student Diaries PLP 2',
            'url' => 'aktivitas/studentdiaries/plp2',
            'permission' => 'aktivitas/studentdiaries/plp2-read'
        ],
        [
            'name' => 'Diary Verifications PLP 1',
            'url' => 'aktivitas/diaryverifications/plp1',
            'permission' => 'aktivitas/diaryverifications/plp1-read'
        ],
        [
            'name' => 'Diary Verifications PLP 2',
            'url' => 'aktivitas/diaryverifications/plp2',
            'permission' => 'aktivitas/diaryverifications/plp2-read'
        ],
        [
            'name' => 'Rekap Nilai PLP 1',
            'url' => 'aktivitas/schoolassessments/plp1',
            'permission' => 'aktivitas/schoolassessments/plp1-read'
        ],
        [
            'name' => 'Rekap Nilai PLP 2',
            'url' => 'aktivitas/schoolassessments/plp2',
            'permission' => 'aktivitas/schoolassessments/plp2-read'
        ],
        [
            'name' => 'Nilai N1',
            'url' => 'aktivitas/schoolassessments/plp2/2022N1',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N1-read'
        ],
        [
            'name' => 'Nilai N2.1',
            'url' => 'aktivitas/schoolassessments/plp1/2022N2',
            'permission' => 'aktivitas/schoolassessments/plp1/2022N2-read'
        ],
        [
            'name' => 'Nilai N2.2',
            'url' => 'aktivitas/schoolassessments/plp2/2022N2',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N2-read'
        ],
        [
            'name' => 'Nilai N3',
            'url' => 'aktivitas/schoolassessments/plp2/2022N3',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N3-read'
        ],
        [
            'name' => 'Nilai N4',
            'url' => 'aktivitas/schoolassessments/plp2/2022N4',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N4-read'
        ],
        [
            'name' => 'Nilai N5',
            'url' => 'aktivitas/schoolassessments/plp2/2022N5',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N5-read'
        ],
        [
            'name' => 'Nilai N6',
            'url' => 'aktivitas/schoolassessments/plp2/2022N6',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N6-read'
        ],
        [
            'name' => 'Nilai N7',
            'url' => 'aktivitas/schoolassessments/plp2/2022N7',
            'permission' => 'aktivitas/schoolassessments/plp2/2022N7-read'
        ],
        [
            'name' => 'Nilai N8',
            'url' => 'aktivitas/schoolassessments/plp1/2022N8',
            'permission' => 'aktivitas/schoolassessments/plp1/2022N8-read'
        ],
        [
            'name' => 'Student Diaries',
            'url' => 'aktivitas/studentdiaries/plp',
            'permission' => 'aktivitas/studentdiaries/plp-read'
        ],
        [
            'name' => 'Diary Verifications',
            'url' => 'aktivitas/diaryverifications/plp',
            'permission' => 'aktivitas/diaryverifications/plp-read'
        ],
        [
            'name' => 'Student Observations',
            'url' => 'aktivitas/studentobservations/plp',
            'permission' => 'aktivitas/studentobservations/plp-read'
        ],
        [
            'name' => 'School Assessments',
            'url' => 'aktivitas/schoolassessments/plp',
            'permission' => 'aktivitas/schoolassessments/plp-read'
        ],
        [
            'name' => 'Teaching Responsibility',
            'url' => 'aktivitas/teachingrespons',
            'permission' => 'aktivitas/teachingrespons-read'
        ],

        // LAPORAN UMUM
        [
            'name' => 'Mapping Result',
            'url' => 'report/mappingresult',
            'permission' => 'report/mappingresult-read'
        ],
        [
            'name' => 'Mapping Quota',
            'url' => 'report/mappingquota',
            'permission' => 'report/mappingquota-read'
        ],
        [
            'name' => 'Summary',
            'url' => 'report/summary',
            'permission' => 'report/summary-read'
        ],
        [
            'name' => 'School User Proposal Report',
            'url' => 'report/schooluserproposal',
            'permission' => 'report/schooluserproposal-read'
        ],
        [
            'name' => 'Profile Progress',
            'url' => 'data/progress/profile',
            'permission' => 'data/progress/profile-read'
        ],

        // KAJUR: yudisium/progress PLP per periode
        [
            'name' => 'Yudisium PLP 1',
            'url' => 'yudisium/plp1',
            'permission' => 'yudisium/plp1-read'
        ],
        [
            'name' => 'Yudisium PLP 2',
            'url' => 'yudisium/plp2',
            'permission' => 'yudisium/plp2-read'
        ],
        [
            'name' => 'Progress PLP 1',
            'url' => 'data/progress/plp1',
            'permission' => 'data/progress/plp1-read'
        ],
        [
            'name' => 'Progress PLP 2',
            'url' => 'data/progress/plp2',
            'permission' => 'data/progress/plp2-read'
        ],
        [
            'name' => 'Yudisium PLP',
            'url' => 'yudisium/plp',
            'permission' => 'yudisium/plp-read'
        ],
        [
            'name' => 'Progress PLP',
            'url' => 'data/progress/plp',
            'permission' => 'data/progress/plp-read'
        ],

        // MODE ONLY: endpoint tanpa suffix plp1/plp2
        [
            'name' => 'Summary PLP',
            'url' => 'report/summary/plp',
            'permission' => 'report/summary/plp-read'
        ],
    ],
];
