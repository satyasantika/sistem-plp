<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'subject_id',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'provider',
        'is_pns',
        'golongan',
        'npwp',
        'nomor_rekening',
        'bank'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        // 'is_pns' => 'boolean',
        // 'is_active' => 'boolean',
    ];

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function headmasters()
    {
        return $this->hasMany(School::class, 'headmaster_id');
    }

    public function coordinators()
    {
        return $this->hasMany(School::class, 'coordinator_id');
    }

    public function students()
    {
        return $this->hasMany(Map::class, 'student_id');
    }

    public function lectures()
    {
        return $this->hasMany(Map::class, 'lecture_id');
    }

    public function teachers()
    {
        return $this->hasMany(Map::class, 'teacher_id');
    }
}
