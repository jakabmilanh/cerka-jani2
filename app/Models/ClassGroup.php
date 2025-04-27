<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'name', 'description'];

    // Az osztályt létrehozó tanár
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_group_id');
    }

    // Az osztályhoz tartozó diákok (User modelből, akiknek a role "diák")
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_group_student', 'class_group_id', 'user_id')
            ->withTimestamps();
    }
}
