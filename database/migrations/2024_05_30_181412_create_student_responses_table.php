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
        Schema::create('student_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id');
            $table->foreign('test_id')
                ->references('id')
                ->on('tests')
                ->onDelete('cascade');
            $table->unsignedBigInteger('skill_id');
            $table->foreign('skill_id')
                ->references('id')
                ->on('test_skills')
                ->onDelete('cascade');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->longText('text_response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_responses');
    }
};
