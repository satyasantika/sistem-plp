<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'plp1' => 'boolean',
        'plp2' => 'boolean',
        'plp' => 'boolean',
    ];

    public static function availableYears()
    {
        return static::query()
            ->whereNotNull('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(function ($year) {
                return (int) $year;
            })
            ->values();
    }

    public static function availableYearsForUser(?User $user = null)
    {
        $user = $user ?: auth()->user();

        if (!$user) {
            return static::availableYears();
        }

        return static::query()
            ->visibleToUser($user)
            ->whereNotNull('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(function ($year) {
                return (int) $year;
            })
            ->values();
    }

    public static function activeYear(?User $user = null): int
    {
        $availableYears = static::availableYearsForUser($user);
        $defaultYear = (int) config('plp.default_year');

        if ($availableYears->isEmpty()) {
            return $defaultYear;
        }

        $selectedYear = (int) session('active_year', $defaultYear);

        return $availableYears->contains($selectedYear)
            ? $selectedYear
            : (int) $availableYears->first();
    }

    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    public function scopeForActiveYear(Builder $query): Builder
    {
        return $query->forYear(static::activeYear());
    }

    public function scopeVisibleToUser(Builder $query, ?User $user = null): Builder
    {
        $user = $user ?: auth()->user();

        if (!$user) {
            return $query;
        }

        if ($user->hasRole('admin') || $user->can('dashboard/ketua-read')) {
            return $query;
        }

        $headmasterSchoolIds = $user->headmasters()->pluck('id');
        $coordinatorSchoolIds = $user->coordinators()->pluck('id');

        return $query->where(function (Builder $builder) use ($user, $headmasterSchoolIds, $coordinatorSchoolIds) {
            $hasConstraint = false;

            if ($user->can('dashboard/kajur-read') && $user->subject_id) {
                $builder->orWhere('subject_id', $user->subject_id);
                $hasConstraint = true;
            }

            if ($user->can('dashboard/dosen-read')) {
                $builder->orWhere(function (Builder $lectureQuery) use ($user) {
                    $lectureQuery
                        ->where('lecture_id', $user->id)
                        ->where('subject_id', $user->subject_id);
                });
                $hasConstraint = true;
            }

            if ($user->can('dashboard/guru-read')) {
                $builder->orWhere(function (Builder $teacherQuery) use ($user) {
                    $teacherQuery
                        ->where('teacher_id', $user->id)
                        ->where('subject_id', $user->subject_id);
                });
                $hasConstraint = true;
            }

            if ($user->can('dashboard/mahasiswa-read')) {
                $builder->orWhere('student_id', $user->id);
                $hasConstraint = true;
            }

            if ($user->can('dashboard/kepsek-read') && $headmasterSchoolIds->isNotEmpty()) {
                $builder->orWhereIn('school_id', $headmasterSchoolIds);
                $hasConstraint = true;
            }

            if ($user->can('dashboard/korguru-read') && $coordinatorSchoolIds->isNotEmpty()) {
                $builder->orWhereIn('school_id', $coordinatorSchoolIds);
                $hasConstraint = true;
            }

            if (!$hasConstraint) {
                $builder->whereRaw('1 = 0');
            }
        });
    }

    public function students()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lectures()
    {
        return $this->belongsTo(User::class, 'lecture_id');
    }

    public function teachers()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function diaries()
    {
        return $this->hasMany(Diary::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function observations()
    {
        return $this->hasMany(Observation::class);
    }
}
