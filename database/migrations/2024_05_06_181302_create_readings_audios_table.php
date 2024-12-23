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
        Schema::create('readings_audios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_skill_id');
            $table->foreign('test_skill_id')
                ->references('id')
                ->on('test_skills')
                ->onDelete('cascade');
            $table->longText('reading_audio_file');
            $table->string('part_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readings_audios');
    }
};
