<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('roles')) {
            throw new \Exception("A 'roles' tábla nem létezik! Futtasd előbb a create_roles_table migrációt.");
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')
                    ->nullable()
                    ->constrained('roles')
                    ->onDelete('restrict');
                $table->string('address')->nullable();
                $table->date('birth')->nullable();
                $table->string('contact')->nullable();
                $table->string('student_card')->nullable();
                $table->string('id_card')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
        });
    }
};

