<?php

namespace Database\Seeders;

use App\Models\FormItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FormItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvData = fopen(base_path('/database/seeders/csvs/formItems.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                FormItem::create([
                    'form_id' => $data['0'],
                    'component' => $data['1'],
                    'component_order' => $data['2'],
                    'name' => $data['3'],
                    'max_score' => $data['4'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
