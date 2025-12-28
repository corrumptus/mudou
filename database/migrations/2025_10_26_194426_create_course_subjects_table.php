<?php

use App\Models\Course;
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
        Schema::create('course_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('color')->nullable();
            $table->foreignIdFor(Course::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_subjects');
    }
};
