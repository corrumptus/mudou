<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::create([
            "email" => "admin@admin.admin",
            "name" => "admin",
            "password" => Hash::make('admin123'),
            "cpf" => "00000000001",
            "birth_date" => fake()->date(),
            "is_admin" => true,
            "is_teacher" => false,
            "is_student" => false,
            "is_monitor" => false
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where("email", "admin@admin.admin")->first()->delete();
    }
};
