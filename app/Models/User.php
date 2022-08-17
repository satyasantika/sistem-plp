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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'is_pns' => 'boolean',
    ];

    public function subjects()
    {
        $this->belongsTo(Subject::class);
    }

    public function headmasters()
    {
        $this->hasMany(School::class, 'headmaster_id');
    }

    public function coordinators()
    {
        $this->hasMany(School::class, 'coordinator_id');
    }

    public function students()
    {
        $this->hasMany(Map::class, 'student_id');
    }

    public function lectures()
    {
        $this->hasMany(Map::class, 'lecture_id');
    }

    public function teachers()
    {
        $this->hasMany(Map::class, 'teacher_id');
    }
}
