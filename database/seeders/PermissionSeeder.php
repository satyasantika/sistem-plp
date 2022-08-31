<?php

namespace Database\Seeders;

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


        $action = ['read', 'create', 'update', 'delete'];
        $admin_access = [
            'roles',
            'users',
            'permissions',
            'konfigurasi',
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
        $permissions = [];
        foreach ($admin_access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission])->assignRole('admin');
        }

        $kepsek_access = [
            'usulan',
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

        $kajur_access = [
            'mapping',
            'mapping/mysubjects',
            'yudisium',
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

        $nilai_access = [
            'nilai',
            'nilai/resumes',
            'aktivitas',
            'aktivitas/assessments',
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

        $dosen_access = [
            'aktivitas/lecturemonitors',
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

    }
}
