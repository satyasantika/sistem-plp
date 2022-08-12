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
        Navigation::create([
            'name' => 'Konfigurasi',
            'url' => 'konfigurasi',
            'icon' => 'ti-hummer',
            'parent_id' => null,
            'order' => 1,
        ]);
        Navigation::create([
            'name' => 'Role',
            'url' => 'konfigurasi/roles',
            'icon' => '',
            'parent_id' => 1,
            'order' => 2,
        ]);
        Navigation::create([
            'name' => 'Permission',
            'url' => 'konfigurasi/permissions',
            'icon' => '',
            'parent_id' => 1,
            'order' => 3,
        ]);

    }
}
