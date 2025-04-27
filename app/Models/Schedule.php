<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_group_id',
        'teacher_id',
        'subject_id',
        'date',
        'time',
        'end_time',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class, 'class_group_id');
    }

    // A tanár, aki ezt az órarendet létrehozta
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
