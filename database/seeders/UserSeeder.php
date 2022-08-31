<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

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
        // $role = Role::create(['name' => 'dosen']);
        $csvData = fopen(base_path('/database/seeders/csvs/lectures.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                User::create([
                    'username'  => $data[0],
                    'name'      => $data[1],
                    'phone'     => $data[2],
                    'email'     => $data[3],
                    'password'  => Hash::make($data[5]),
                    'subject_id' => $data[4],
                    'address'   => $data[6],
                ])->assignRole('dosen');
            }
            $transRow = false;
        }
        fclose($csvData);

        // $role = Role::create(['name' => 'mahasiswa']);
        $csvData = fopen(base_path('/database/seeders/csvs/students.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                User::create([
                    'username'  => $data[0],
                    'name'      => $data[1],
                    'email'     => $data[2],
                    'password'  => Hash::make($data[3]),
                    'subject_id' => $data[4],
                    'phone'     => $data[5],
                    'address'   => $data[6],
                ])->assignRole('mahasiswa');
            }
            $transRow = false;
        }
        fclose($csvData);

        // $role = Role::create(['name' => 'guru']);
        $csvData = fopen(base_path('/database/seeders/csvs/teachers.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                User::create([
                    'username'  => $data[0],
                    'name'      => $data[1],
                    'email'     => $data[2],
                    'password'  => Hash::make($data[3]),
                    'subject_id' => $data[4],
                ])->assignRole('guru');
            }
            $transRow = false;
        }
        fclose($csvData);

        // $role = Role::create(['name' => 'kepsek']);
        $csvData = fopen(base_path('/database/seeders/csvs/headmasters.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                User::create([
                    'username'  => $data[0],
                    'name'      => $data[1],
                    'email'     => $data[2],
                    'password'  => Hash::make($data[3]),
                ])->assignRole('kepsek');
            }
            $transRow = false;
        }
        fclose($csvData);

        // $role = Role::create(['name' => 'korgur']);
        $csvData = fopen(base_path('/database/seeders/csvs/coordinators.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                User::create([
                    'username'  => $data[0],
                    'name'      => $data[1],
                    'email'     => $data[2],
                    'password'  => Hash::make($data[3]),
                ])->assignRole('korguru');
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
