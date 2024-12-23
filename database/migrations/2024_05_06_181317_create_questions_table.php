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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_skill_id');
            $table->foreign('test_skill_id')
                ->references('id')
                ->on('test_skills')
                ->onDelete('cascade');
            $table->unsignedBigInteger('reading_audio_id')->nullable();
            $table->foreign('reading_audio_id')
                ->references('id')
                ->on('readings_audios')
                ->onDelete('cascade');
            $table->integer('question_number')->nullable();
            $table->string('part_name')->nullable();
            $table->longText('question_text');
            $table->string('question_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
