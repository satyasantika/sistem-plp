<?php

namespace App\Exports;

use App\Models\Map;
use App\Models\Assessment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportYudisium implements FromView
{
    public function view(): View
    {
        $plp_order = request()->segment(2);

        $maps = Map::where([
            'year'=>2023,
            'subject_id'=>51,
            $plp_order=>1
            ])
            ->whereNotNull('student_id')
            ->orderBy('subject_id')->get();

        foreach ($maps as $key => $map) {

            $npm = $map->students->username;
            $student = $map->students->name;
            $departement = $map->subjects->departement;
            $school = $map->schools->name;
            $lecture = $map->lectures->name;
            $teacher = $map->teachers->name;

            if ($plp_order == 'plp2') {
                $collection = collect([
                    'npm',
                    'student',
                    'departement',
                    'lecture',
                    'school',
                    'grade',
                    'letter',
                    'description',
                    'teacher',
                    'teacher_grade',
                    'lecture_grade']);

                $assessment = Assessment::where([
                    'map_id' => $map->id,
                    'plp_order' => 2]);

                $teacher_grade = $assessment->where('assessor','guru')->sum('grade')/13;
                $lecture_grade = $assessment->where('assessor','dosen')->sum('grade')/3;

                $grade = $map->grade2;
                $letter = $map->letter2;
                $description = ($grade < 61) ? 'TIDAK LULUS' : 'LULUS';

                $combined = $collection->combine([
                    $npm,
                    $student,
                    $departement,
                    $lecture,
                    $school,
                    $grade,
                    $letter,
                    $description,
                    $teacher,
                    $teacher_grade,
                    $lecture_grade]);
            } else {
                $collection = collect([
                    'npm',
                    'student',
                    'departement',
                    'lecture',
                    'school',
                    'grade',
                    'letter',
                    'description']);
                $grade = $map->grade1;
                $letter = $map->letter1;
                $description = ($grade < 61) ? 'TIDAK LULUS' : 'LULUS';

                $combined = $collection->combine([
                    $npm,
                    $student,
                    $departement,
                    $lecture,
                    $school,
                    $grade,
                    $letter,
                    $description]);
            }
        }
        $combined->all();
        dd($combined);
        return view('exports.yudisium', [
            // 'maps' => $maps,
            'maps' => $combined,
        ]);
    }

}
