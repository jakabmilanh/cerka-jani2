<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTeacherTable extends Migration
{
    public function up()
    {
        Schema::create('student_teacher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            // Idegen kulcsok beállítása
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            // Egyediségi feltétel: egy diák csak egyszer tartozhat ugyanahhoz a tanárhoz
            $table->unique(['user_id', 'teacher_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_teacher');
    }
}
