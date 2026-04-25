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
        $adminOverview = [];
        $adminSubjectSummaries = collect();
        $adminSchoolSummaries = collect();

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
                ->whereNotNull('student_id')
                ->with(['students', 'schools'])
                ->orderBy('student_id')
                ->get();
        }

        if ($user->can('dashboard/guru-read')) {
            $teacherMaps = Map::forYear($activeYear)
                ->where('subject_id', $user->subject_id)
                ->where('teacher_id', $user->id)
                ->whereNotNull('student_id')
                ->with(['students', 'schools'])
                ->orderBy('student_id')
                ->get();
        }

        if ($user->can('dashboard/kepsek-read')) {
            $headmasterSchools = School::where('headmaster_id', $user->id)
                ->with([
                    'maps' => function ($query) use ($activeYear) {
                        $query->forYear($activeYear)
                            ->with(['subjects', 'students', 'teachers', 'lectures']);
                    }
                ])
                ->get();
        }

        if ($user->can('dashboard/korguru-read')) {
            $teacherCoordinatorSchools = School::where('coordinator_id', $user->id)
                ->with([
                    'maps' => function ($query) use ($activeYear) {
                        $query->forYear($activeYear)
                            ->with(['subjects', 'students', 'teachers', 'lectures']);
                    }
                ])
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

        if ($user->can('dashboard/ketua-read') || $user->hasRole('admin')) {
            $adminYearMaps = Map::forYear($activeYear);

            $totalMaps = (int) (clone $adminYearMaps)->count();
            $filledStudents = (int) (clone $adminYearMaps)->whereNotNull('student_id')->distinct('student_id')->count('student_id');
            $mappedLectures = (int) (clone $adminYearMaps)->whereNotNull('lecture_id')->distinct('lecture_id')->count('lecture_id');
            $mappedTeachers = (int) (clone $adminYearMaps)->whereNotNull('teacher_id')->distinct('teacher_id')->count('teacher_id');
            $activeSchools = (int) (clone $adminYearMaps)->whereNotNull('school_id')->distinct('school_id')->count('school_id');
            $activeSubjects = (int) (clone $adminYearMaps)->whereNotNull('subject_id')->distinct('subject_id')->count('subject_id');

            $adminOverview = [
                'total_maps' => $totalMaps,
                'filled_students' => $filledStudents,
                'mapped_lectures' => $mappedLectures,
                'mapped_teachers' => $mappedTeachers,
                'active_schools' => $activeSchools,
                'active_subjects' => $activeSubjects,
                'student_fill_rate' => $totalMaps > 0 ? round(($filledStudents / $totalMaps) * 100, 1) : 0,
            ];

            $adminSubjectSummaries = Map::forYear($activeYear)
                ->select(
                    'subject_id',
                    DB::raw('COUNT(*) as quota_count'),
                    DB::raw('COUNT(student_id) as filled_count'),
                    DB::raw('COUNT(DISTINCT school_id) as school_count'),
                    DB::raw('COUNT(DISTINCT lecture_id) as lecture_count'),
                    DB::raw('COUNT(DISTINCT teacher_id) as teacher_count')
                )
                ->groupBy('subject_id')
                ->with('subjects')
                ->orderByDesc('filled_count')
                ->get();

            $adminSchoolSummaries = Map::forYear($activeYear)
                ->select(
                    'school_id',
                    DB::raw('COUNT(*) as quota_count'),
                    DB::raw('COUNT(student_id) as filled_count'),
                    DB::raw('COUNT(DISTINCT subject_id) as subject_count')
                )
                ->groupBy('school_id')
                ->with('schools')
                ->orderByDesc('filled_count')
                ->limit(15)
                ->get();
        }

        return view('dashboard', compact(
            'studentMaps',
            'studentSchoolmates',
            'lectureMaps',
            'teacherMaps',
            'headmasterSchools',
            'teacherCoordinatorSchools',
            'departementSchoolSummaries',
            'departementLectureSummaries',
            'adminOverview',
            'adminSubjectSummaries',
            'adminSchoolSummaries'
        ));
    }
}
