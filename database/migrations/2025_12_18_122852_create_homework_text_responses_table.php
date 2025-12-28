<?php

use App\Models\Homework;
use App\Models\User;
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
        Schema::create('homework_text_responses', function (Blueprint $table) {
            $table->longText('text');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Homework::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework_text_responses');
    }
};
