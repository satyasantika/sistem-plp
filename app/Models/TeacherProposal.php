<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherProposal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'registered' => 'boolean',
    ];

    public function schools()
    {
        return $this->belongsTo(School::class);
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class);
    }
}
