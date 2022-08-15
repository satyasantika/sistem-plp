<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Subject::truncate();
        $csvData = fopen(base_path('/database/seeders/csvs/subjects.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                Subject::create([
                    'id' => $data['0'],
                    'name' => $data['1'],
                    'departement' => $data['2'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
