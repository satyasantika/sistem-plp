<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function formItems()
    {
        return $this->hasMany(FormItem::class);
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
