<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'address',
        'birth',
        'contact',
        'student_card',
        'id_card',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'student_teacher', 'user_id', 'teacher_id')->withTimestamps();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function classGroups()
    {
        return $this->belongsToMany(ClassGroup::class, 'class_group_student', 'user_id', 'class_group_id')
            ->withTimestamps();
    }
    
}
