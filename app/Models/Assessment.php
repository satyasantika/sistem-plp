<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'exam_date' => 'date',
    ];

    public function maps()
    {
        return $this->belongsTo(Map::class, 'map_id');
    }

    public function forms()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
