<?php

use App\Models\DayClass;
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
        Schema::create('class_elements', function (Blueprint $table) {
            $table->string('name');
            $table->string('type');
            $table->foreignId('element_id')->nullable();
            $table->foreignIdFor(DayClass::class, 'class_id');

            $table->primary([ 'type', 'element_id', 'class_id' ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_elements');
    }
};
