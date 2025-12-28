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
        Schema::create('files', function (Blueprint $table) {
            $table->uuid();

            // infos do arquivo
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();

            // relacionamento polimórfico
            // $table->morphs('model');

            // tipo do arquivo (avatar, aula, resposta, enunciado, etc.)
            $table->string('context')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
