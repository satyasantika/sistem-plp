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
            'icon' => '',
            'order' => Navigation::count() + 1,
        ]);
        // $konfigurasi->children()->create([
        //     'name' => 'Permission',
        //     'url' => 'konfigurasi/permissions',
        //     'icon' => '',
        // ]);

    }
}
