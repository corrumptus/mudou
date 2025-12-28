<?php

use App\Models\Classroom;
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
        Schema::create('classroom_periods', function (Blueprint $table) {
            $table->foreignIdFor(Classroom::class)->index();
            $table->string('dayOfTheWeek', 13);
            $table->time('beginTime');
            $table->time('closeTime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_periods');
    }
};
