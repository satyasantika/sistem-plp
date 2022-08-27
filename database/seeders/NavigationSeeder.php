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
        $konfigurasi = Navigation::create([
            'name' => 'Konfigurasi',
            'url' => 'konfigurasi',
            'icon' => 'ti-settings',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        $konfigurasi->children()->create([
            'name' => 'Role',
            'url' => 'konfigurasi/roles',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);

        $konfigurasi->children()->create([
            'name' => 'Permission',
            'url' => 'konfigurasi/permissions',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);

        $konfigurasi->children()->create([
            'name' => 'Navigation',
            'url' => 'konfigurasi/navigations',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);

        $konfigurasi->children()->create([
            'name' => 'User',
            'url' => 'konfigurasi/users',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);

        $konfigurasi->children()->create([
            'name' => 'School',
            'url' => 'konfigurasi/schools',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);

        $konfigurasi->children()->create([
            'name' => 'User Proposal',
            'url' => 'konfigurasi/schooluserproposals',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);

        $konfigurasi->children()->create([
            'name' => 'Mapping',
            'url' => 'konfigurasi/maps',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);

        $navigation = Navigation::create([
            'name' => 'Usulan',
            'url' => 'usulan',
            'icon' => '',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);

        $navigation->children()->create([
            'name' => 'Koordinator GP',
            'url' => 'usulan/coordinators',
            'icon' => '',
            'order' => Navigation::count() + 1,
        ]);

        $navigation->children()->create([
            'name' => 'Guru Pamong',
            'url' => 'usulan/teachers',
            'icon' => '',
            'order' => Navigation::count() + 1,
        ]);
    }
}
