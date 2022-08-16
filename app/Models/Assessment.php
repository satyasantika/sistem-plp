<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    public function forms()
    {
        return $this->belongsTo(Form::class);
    }
}
