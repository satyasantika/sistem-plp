<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'plp1' => 'boolean',
        'plp2' => 'boolean',
    ];

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
        return $this->belongsTo(School::class);
    }

    public function dairies()
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
