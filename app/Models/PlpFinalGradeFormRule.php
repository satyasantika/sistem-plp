<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlpFinalGradeFormRule extends Model
{
    protected $fillable = [
        'year',
        'plp_order',
        'assessor',
        'form_id',
        'times',
    ];

    protected $casts = [
        'year' => 'integer',
        'plp_order' => 'integer',
        'times' => 'integer',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }
}
