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
            'konfigurasi/evaluations',
            'konfigurasi/observations',
            'konfigurasi/diaries',
        ];

        $usulan_data = [
            'usulan/school-coordinators',
            'usulan/school-teachers',
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
                'icon' => 'ti-setting',
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
    }
}
