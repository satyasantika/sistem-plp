<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvData = fopen(base_path('/database/seeders/csvs/subjects.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (! $transRow) {
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
