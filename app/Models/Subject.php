<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function teacher_proposals()
    {
        return $this->hasMany(TeacherProposal::class);
    }
}
