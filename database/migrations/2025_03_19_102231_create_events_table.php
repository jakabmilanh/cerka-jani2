<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->default(1)->constrained()->onDelete('cascade');
            $table->string('day', 10);
            $table->time('time');
            $table->time('end_time');
            $table->string('title', 255);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('events');
    }
};
