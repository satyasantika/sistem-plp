<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    function students()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    function lectures()
    {
        return $this->belongsTo(User::class, 'lecture_id');
    }

    function teachers()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    function schools()
    {
        return $this->belongsTo(School::class);
    }

    protected $casts = [
        'plp1' => 'boolean',
        'plp2' => 'boolean',
    ];
}
