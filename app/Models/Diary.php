<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    function map()
    {
        return $this->belongsTo(Map::class);
    }
}
