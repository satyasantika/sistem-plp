<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::truncate();
        $csvData = fopen(base_path('/database/seeders/csvs/lectures.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                User::create([
                    'username' => $data[0],
                    'name'     => $data[1],
                    'phone' => $data[2],
                    'email'    => $data[3],
                    'password' => Hash::make('dosen'.$data[0]),
                    'subject_id' => $data[4],
                ])->assignRole('dosen');
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
