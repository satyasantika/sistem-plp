<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function maps()
    {
        return $this->hasMany(Map::class);
    }

    public function headmasters()
    {
        return $this->belongsTo(User::class,'headmaster_id');
    }

    public function coordinators()
    {
        return $this->belongsTo(User::class,'coordinator_id');
    }

    public function teacher_proposals()
    {
        return $this->hasMany(TeacherProposal::class);
    }

    public function coordinator_proposals()
    {
        return $this->hasMany(CoordinatorProposal::class);
    }

}
