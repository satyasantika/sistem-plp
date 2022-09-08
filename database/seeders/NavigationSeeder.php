<?php

namespace Database\Seeders;

use App\Models\Navigation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $konfigurasi_data = [
            'konfigurasi/roles',
            'konfigurasi/permissions',
            'konfigurasi/navigations',
            'konfigurasi/users',
            'konfigurasi/schools',
            'konfigurasi/schooluserproposals',
            'konfigurasi/maps',
            'konfigurasi/forms',
            'konfigurasi/formitems',
            'konfigurasi/assessments',
            'konfigurasi/observations',
            'konfigurasi/diaries',
        ];

        $usulan_data = [
            'usulan/school_coordinators',
            'usulan/school_teachers',
        ];

        $mapping_data = [
            'mapping/mastermaps',
            'mapping/departementmaps',
            'mapping/teachermaps',
        ];

        $aktivitas_data = [
            'aktivitas/studentdiaries',
            'aktivitas/diaryverifications',
            'aktivitas/studentobservations',
            'aktivitas/schoolassessments',
        ];

        $konfigurasi = Navigation::create([
            'name' => 'Konfigurasi',
            'url' => 'konfigurasi',
            'icon' => 'ti-settings',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($konfigurasi_data as $child) {
            $part = explode('/',$child);
            $konfigurasi->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => 'ti-settings',
                'order' => Navigation::count() + 1,
            ]);
        }

        $usulan = Navigation::create([
            'name' => 'Usulan',
            'url' => 'usulan',
            'icon' => '',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($usulan_data as $child) {
            $part = explode('/',$child);
            $usulan->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }

        $mapping = Navigation::create([
            'name' => 'Mapping',
            'url' => 'mapping',
            'icon' => '',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($mapping_data as $child) {
            $part = explode('/',$child);
            $mapping->children()->create([
                'name' => $part[1],
                'url' => $child,
                'icon' => '',
                'order' => Navigation::count() + 1,
            ]);
        }
        $aktivitas = Navigation::create([
            'name' => 'Aktivitas',
            'url' => 'aktivitas',
            'icon' => '',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        foreach ($aktivitas_data as $child) {
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
