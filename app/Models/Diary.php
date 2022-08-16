<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function maps()
    {
        return $this->belongsTo(Map::class);
    }
}
