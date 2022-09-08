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

        $alone_access = [
            'aktivitas/studentdiaries/plp1-read',
            'aktivitas/studentdiaries/plp2-read',
            'aktivitas/diaryverifications/plp1-read',
            'aktivitas/diaryverifications/plp2-read',
            'aktivitas/schoolassessments/plp1/2022N2-read',
            'aktivitas/schoolassessments/plp1/2022N1-read',
            'aktivitas/schoolassessments/plp2/2022N2-read',
            'aktivitas/schoolassessments/plp2/2022N3-read',
            'aktivitas/schoolassessments/plp2/2022N4-read',
            'aktivitas/schoolassessments/plp2/2022N5-read',
            'aktivitas/schoolassessments/plp2/2022N6-read',
            'aktivitas/schoolassessments/plp2/2022N7-read',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('admin');
        }

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
            'konfigurasi/roles',
            'konfigurasi/permissions',
            'konfigurasi/users',
            'konfigurasi/rolepermissions',
            'konfigurasi/userroles',
            'konfigurasi/userpermissions',
            'konfigurasi/schools',
            'konfigurasi/schooluserproposals',
            'konfigurasi/maps',
            'konfigurasi/navigations',
            'konfigurasi/forms',
            'konfigurasi/formitems',
            'konfigurasi/assessments',
            'konfigurasi/observations',
            'konfigurasi/diaries',
        ];

        // Role Admin
        $permissions = [];
        foreach ($admin_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('admin');
        }

        $konfigurasi = Navigation::create([
            'name' => 'Konfigurasi',
            'url' => 'konfigurasi',
            'icon' => 'ti-settings',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($admin_access as $child) {
            $part = explode('/',$child);
            $konfigurasi->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => 'ti-settings',
                'order' => Navigation::count() + 1,
            ]);
        }

        // Role Kepsek
        $kepsek_access = [
            'usulan/school_coordinators',
            'usulan/school_teachers',
        ];
        $permissions = [];
        foreach ($kepsek_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
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
            $part = explode('/',$child);
            $usulan->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        // Role Kajur
        $kajur_access = [
            'mapping/mysubjects',
            'mapping/departementmaps',
        ];
        $permissions = [];
        foreach ($kajur_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
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
            $part = explode('/',$child);
            $mapping->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        $kajur_access = [
            'yudisium/first',
            'yudisium/second',
        ];
        $permissions = [];
        foreach ($kajur_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
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
            $part = explode('/',$child);
            $yudisium->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        // Role Dosen, Guru
        $nilai_access = [
            'aktivitas/schoolassessments',
        ];
        $permissions = [];
        foreach ($nilai_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
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
            $part = explode('/',$child);
            $aktivitas->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        $dosen_access = [
            'aktivitas/lecturemonitors',
            'aktivitas/diaryverifications',
        ];
        $permissions = [];
        foreach ($dosen_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('dosen');
        }

        foreach ($dosen_access as $child) {
            $part = explode('/',$child);
            $aktivitas->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        $mahasiswa_access = [
            'aktivitas/studentobservations',
            'aktivitas/studentdiaries',
        ];
        $permissions = [];
        foreach ($mahasiswa_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('mahasiswa');
        }

        foreach ($mahasiswa_access as $child) {
            $part = explode('/',$child);
            $aktivitas->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        $sekolah_access = [
            'mapping/teachermaps',
        ];
        $permissions = [];
        foreach ($sekolah_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->syncRoles(['kepsek','korguru']);
        }

        foreach ($sekolah_access as $child) {
            $part = explode('/',$child);
            $aktivitas->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

    }
}
