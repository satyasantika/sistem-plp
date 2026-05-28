<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlpFinalGradeWeight extends Model
{
    protected $fillable = [
        'year',
        'plp_order',
        'dosen_weight',
        'guru_weight',
    ];

    protected $casts = [
        'year' => 'integer',
        'plp_order' => 'integer',
        'dosen_weight' => 'float',
        'guru_weight' => 'float',
    ];

    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    public static function defaultsForPlp2(): array
    {
        return [
            'dosen_weight' => 0.4,
            'guru_weight' => 0.6,
        ];
    }
}
