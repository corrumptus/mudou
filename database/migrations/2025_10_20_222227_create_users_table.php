<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("email")->unique()->index();
            $table->string("name");
            $table->string("password")->nullable();
            $table->string("cpf", 11)->unique();
            $table->date("birth_date");
            $table->boolean("is_admin");
            $table->boolean("is_teacher");
            $table->boolean("is_student");
            $table->boolean("is_monitor");
            $table->boolean("profile_picture_hash")->nullable();
            $table->boolean("is_first_access")->default(true);
            $table->boolean("is_active")->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("users");
    }
};
