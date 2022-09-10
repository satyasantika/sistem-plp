<?php

namespace Database\Seeders;

use App\Models\Navigation;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'dekanat']);
        Role::create(['name' => 'ketua']);
        Role::create(['name' => 'sekretariat']);
        Role::create(['name' => 'akademik']);
        Role::create(['name' => 'data']);
        Role::create(['name' => 'dosen']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'mahasiswa']);
        Role::create(['name' => 'kajur']);
        Role::create(['name' => 'kepsek']);
        Role::create(['name' => 'korguru']);
        Role::create(['name' => 'kordosen']);

        Permission::create(['name' => 'dashboard/dekanat-read'])->assignRole('dekanat');
        Permission::create(['name' => 'dashboard/ketua-read'])->assignRole('ketua');
        Permission::create(['name' => 'dashboard/sekretariat-read'])->assignRole('sekretariat');
        Permission::create(['name' => 'dashboard/akademik-read'])->assignRole('akademik');
        Permission::create(['name' => 'dashboard/data-read'])->assignRole('data');
        Permission::create(['name' => 'dashboard/dosen-read'])->assignRole('dosen');
        Permission::create(['name' => 'dashboard/guru-read'])->assignRole('guru');
        Permission::create(['name' => 'dashboard/mahasiswa-read'])->assignRole('mahasiswa');
        Permission::create(['name' => 'dashboard/kajur-read'])->assignRole('kajur');
        Permission::create(['name' => 'dashboard/kepsek-read'])->assignRole('kepsek');
        Permission::create(['name' => 'dashboard/korguru-read'])->assignRole('korguru');
        Permission::create(['name' => 'dashboard/kordosen-read'])->assignRole('kordosen');

        $action = ['read', 'create', 'update', 'delete'];

        // permission all complete
        $alone_access = [
            'roles',
            'users',
            'permissions',
        ];
        $permissions = [];
        foreach ($alone_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('admin');
        }

        $admin_access = [
            ['role','konfigurasi/roles'],
            ['permission','konfigurasi/permissions'],
            ['user','konfigurasi/users'],
            ['role permission','konfigurasi/rolepermissions'],
            ['user role','konfigurasi/userroles'],
            ['user permission','konfigurasi/userpermissions'],
            ['school','konfigurasi/schools'],
            ['school user proposal','konfigurasi/schooluserproposals'],
            ['map','konfigurasi/maps'],
            ['navigation','konfigurasi/navigations'],
            ['form','konfigurasi/forms'],
            ['form item','konfigurasi/formitems'],
            ['assessment','konfigurasi/assessments'],
            ['observation','konfigurasi/observations'],
            ['diary','konfigurasi/diaries'],
        ];

        // Role Admin
        $permissions = [];
        foreach ($admin_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1[1].'-'.$value2);
            }
        }
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('admin');
        }
        // MENU konfigurasi
        $konfigurasi = Navigation::create([
            'name' => 'Konfigurasi',
            'url' => 'konfigurasi',
            'icon' => 'ti-settings',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);
        foreach ($admin_access as $child) {
            // $part = explode('/',$child);
            $konfigurasi->children()->create([
                'name' => $child[0],
                'url' => $child[1],
                'icon' => 'ti-settings',
                'order' => Navigation::count() + 1,
            ]);
        }

        // Role Kepsek
        $kepsek_access = [
            ['Usulan Koor GP','usulan/school_coordinators'],
            ['Usulan Guru','usulan/school_teachers'],
        ];
        $permissions = [];
        foreach ($kepsek_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1[1].'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('kepsek');
        }

        $usulan = Navigation::create([
            'name' => 'Usulan',
            'url' => 'usulan',
            'icon' => '',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($kepsek_access as $child) {
            // $part = explode('/',$child);
            $usulan->children()->create([
                'name' => $child[0],
                'url' => $child[1],
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        // Role Kajur
        $kajur_access = [
            // ['mysubject','mapping/mysubjects'],
            ['Mapping Jurusan','mapping/departementmaps'],
        ];
        $permissions = [];
        foreach ($kajur_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1[1].'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('kajur');
        }

        $mapping = Navigation::create([
            'name' => 'Mapping',
            'url' => 'mapping',
            'icon' => '',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($kajur_access as $child) {
            // $part = explode('/',$child);
            $mapping->children()->create([
                'name' => $child[0],
                'url' => $child[1],
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        $kajur_access = [
            ['Yudisium PLP 1','yudisium/plp1'],
            ['Yudisium PLP 2','yudisium/plp2'],
        ];
        $permissions = [];
        foreach ($kajur_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1[1].'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('kajur');
        }

        $yudisium = Navigation::create([
            'name' => 'Yudisium',
            'url' => 'yudisium',
            'icon' => '',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($kajur_access as $child) {
            // $part = explode('/',$child);
            $yudisium->children()->create([
                'name' => $child[0],
                'url' => $child[1],
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        // Role Dosen, Guru
        $nilai_access = [
            ['Rekap Nilai PLP 1','aktivitas/schoolassessments/plp1'],
            ['Rekap Nilai PLP 2','aktivitas/schoolassessments/plp2'],
            ['Nilai N6','aktivitas/schoolassessments/plp2/2022N6'],
            ['Nilai N7','aktivitas/schoolassessments/plp2/2022N7'],
        ];
        $permissions = [];
        foreach ($nilai_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1[1].'-'.$value2);
            }
        }
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->syncRoles(['dosen','guru']);
        }

        $aktivitas = Navigation::create([
            'name' => 'Aktivitas',
            'url' => 'aktivitas',
            'icon' => '',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($nilai_access as $child) {
            // $part = explode('/',$child);
            $aktivitas->children()->create([
                'name' => $child[0],
                'url' => $child[1],
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        // Role Guru
        $nilai_access = [
            ['Nilai N1','aktivitas/schoolassessments/plp2/2022N1'],
            ['Nilai N3','aktivitas/schoolassessments/plp2/2022N3'],
            ['Nilai N4','aktivitas/schoolassessments/plp2/2022N4'],
            ['Nilai N5','aktivitas/schoolassessments/plp2/2022N5'],
        ];
        $permissions = [];
        foreach ($nilai_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1[1].'-'.$value2);
            }
        }
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('guru');
        }

        $aktivitas = Navigation::create([
            'name' => 'Aktivitas',
            'url' => 'aktivitas',
            'icon' => '',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($nilai_access as $child) {
            // $part = explode('/',$child);
            $aktivitas->children()->create([
                'name' => $child[0],
                'url' => $child[1],
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        // Role Dosen
        $dosen_access = [
            ['Monitoring','aktivitas/lecturemonitors'],
            ['Verifikasi Logbook 1','aktivitas/diaryverifications/plp1'],
            ['Verifikasi Logbook 2','aktivitas/diaryverifications/plp2'],
            ['Nilai N2.1','aktivitas/schoolassessments/plp1/2022N2'],
            ['Nilai N2.2','aktivitas/schoolassessments/plp2/2022N2'],
            ['Nilai N8','aktivitas/schoolassessments/plp1/2022N8'],
        ];
        $permissions = [];
        foreach ($dosen_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1[1].'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('dosen');
        }

        foreach ($dosen_access as $child) {
            // $part = explode('/',$child);
            $aktivitas->children()->create([
                'name' => $child[0],
                'url' => $child[1],
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        $mahasiswa_access = [
            ['Observasi','aktivitas/studentobservations'],
            ['Logbook 1','aktivitas/studentdiaries/plp1'],
            ['Logbook 2','aktivitas/studentdiaries/plp2'],
            ['Catatan Ujian','aktivitas/teachingrespons'],
        ];
        $permissions = [];
        foreach ($mahasiswa_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1[1].'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('mahasiswa');
        }

        foreach ($mahasiswa_access as $child) {
            // $part = explode('/',$child);
            $aktivitas->children()->create([
                'name' => $child[0],
                'url' => $child[1],
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        $sekolah_access = [
            ['Mapping Guru','mapping/teachermaps'],
        ];
        $permissions = [];
        foreach ($sekolah_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1[1].'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->syncRoles(['kepsek','korguru']);
        }

        foreach ($sekolah_access as $child) {
            // $part = explode('/',$child);
            $aktivitas->children()->create([
                'name' => $child[0],
                'url' => $child[1],
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

    }
}
