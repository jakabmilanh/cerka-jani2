<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'email', 'password', 'address', 'birth', 'contact', 'id_card'];


    public function user()
    {
        return $this->belongsToMany(User::class, 'student_teacher', 'teacher_id', 'user_id')->withTimestamps();
    }
}
