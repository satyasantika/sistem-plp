<?php

namespace Database\Seeders;

use App\Models\Map;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvData = fopen(base_path('/database/seeders/csvs/schools.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                School::create([
                    'name' => $data['0'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);

        $csvData = fopen(base_path('/database/seeders/csvs/maps.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                $school = School::where('name',$data['0'])->first();
                for ($i=0; $i < $data['2']; $i++) {
                    Map::create([
                        'school_id' => $school->id,
                        'year' => 2022,
                        'subject_id' => $data['1'],
                    ]);
                }
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
