<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('class_groups', function (Blueprint $table) {
            $table->id();
            // A tanár (User) ID-je, aki az osztályt létrehozta
            $table->unsignedBigInteger('teacher_id');
            $table->string('name');             // Pl.: "9/A", "10/B"
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_groups');
    }
}
