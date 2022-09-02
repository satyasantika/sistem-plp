<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'id' => 'string',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function schoolUserProposals()
    {
        return $this->hasMany(SchoolUserProposal::class);
    }

    public function maps()
    {
        return $this->hasMany(Map::class);
    }
}
