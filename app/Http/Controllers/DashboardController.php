<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $activeYear = Map::activeYear($user);

        $studentMaps = collect();
        $studentSchoolmates = collect();
        $lectureMaps = collect();
        $teacherMaps = collect();
        $headmasterSchools = collect();
        $teacherCoordinatorSchools = collect();
        $departementSchoolSummaries = collect();
        $departementLectureSummaries = collect();

        if ($user->can('dashboard/mahasiswa-read')) {
            $studentMaps = Map::forYear($activeYear)
                ->where('student_id', $user->id)
                ->with([
                    'students.subjects',
                    'subjects',
                    'schools.headmasters',
                    'schools.coordinators',
                    'teachers',
                    'lectures',
                ])
                ->get();

            $schoolIds = $studentMaps->pluck('school_id')->filter()->unique()->values();

            if ($schoolIds->isNotEmpty()) {
                $studentSchoolmates = Map::forYear($activeYear)
                    ->whereIn('school_id', $schoolIds)
                    ->with(['students.subjects'])
                    ->get();
            }
        }

        if ($user->can('dashboard/dosen-read')) {
            $lectureMaps = Map::forYear($activeYear)
                ->where('subject_id', $user->subject_id)
                ->where('lecture_id', $user->id)
                ->with(['students', 'schools'])
                ->get();
        }

        if ($user->can('dashboard/guru-read')) {
            $teacherMaps = Map::forYear($activeYear)
                ->where('subject_id', $user->subject_id)
                ->where('teacher_id', $user->id)
                ->with('students')
                ->get();
        }

        if ($user->can('dashboard/kepsek-read')) {
            $headmasterSchools = School::where('headmaster_id', $user->id)
                ->with(['maps' => function ($query) use ($activeYear) {
                    $query->forYear($activeYear)
                        ->with(['subjects', 'students', 'teachers', 'lectures']);
                }])
                ->get();
        }

        if ($user->can('dashboard/korguru-read')) {
            $teacherCoordinatorSchools = School::where('coordinator_id', $user->id)
                ->with(['maps' => function ($query) use ($activeYear) {
                    $query->forYear($activeYear)
                        ->with(['subjects', 'students', 'teachers', 'lectures']);
                }])
                ->get();
        }

        if ($user->can('dashboard/kajur-read')) {
            $departementSchoolSummaries = Map::forYear($activeYear)
                ->where('subject_id', $user->subject_id)
                ->select('school_id', DB::raw('COUNT(*) as quota_count'), DB::raw('COUNT(student_id) as filled_count'))
                ->groupBy('school_id')
                ->with('schools')
                ->get();

            $lectureSchoolMaps = Map::forYear($activeYear)
                ->where('subject_id', $user->subject_id)
                ->whereNotNull('lecture_id')
                ->select('lecture_id', 'school_id', DB::raw('COUNT(student_id) as total'))
                ->groupBy('lecture_id', 'school_id')
                ->with(['lectures', 'schools'])
                ->get()
                ->groupBy('lecture_id');

            $departementLectureSummaries = $lectureSchoolMaps->map(function ($maps) {
                return (object) [
                    'lecture' => optional($maps->first())->lectures,
                    'schools' => $maps,
                ];
            })->values();
        }

        return view('dashboard', compact(
            'studentMaps',
            'studentSchoolmates',
            'lectureMaps',
            'teacherMaps',
            'headmasterSchools',
            'teacherCoordinatorSchools',
            'departementSchoolSummaries',
            'departementLectureSummaries'
        ));
    }
}
