<?php

use App\Models\Group;
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
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->dateTime('begin_date_time');
            $table->dateTime('close_date_time');
            $table->boolean('can_accept_after_close');
            $table->boolean('is_text');
            $table->integer('max_amount_caracteres');
            $table->boolean('is_file');
            $table->integer('max_amount_files');
            $table->string('file_types');
            $table->foreignIdFor(Group::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework');
    }
};
