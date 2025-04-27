<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassGroupStudentTable extends Migration
{
    public function up()
    {
        Schema::create('class_group_student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_group_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Kötjük a class_group_id-t a class_groups táblához,
            // és a user_id-t a users táblához (csak diákokat várunk)
            $table->foreign('class_group_id')->references('id')->on('class_groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Egyediségi feltétel: egy diák ne kerüljön többször ugyanabba az osztályba
            $table->unique(['class_group_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_group_student');
    }
}
