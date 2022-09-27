<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function maps()
    {
        return $this->belongsTo(Map::class,'map_id');
    }

    public function forms()
    {
        return $this->belongsTo(Form::class,'form_id');
    }
}
