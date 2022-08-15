<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvData = fopen(base_path('/database/seeders/csvs/forms.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                Form::create([
                    'id' => $data['0'],
                    'name' => $data['1'],
                    'count' => $data['2'],
                    'type' => $data['3'],
                    'max_score' => $data['4'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
