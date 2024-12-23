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
        Schema::create('fill_in_the_blanks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('question_homeworks')->onDelete('cascade');
            $table->integer('blank_position');
            $table->string('correct_answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fill_in_the_blanks');
    }
};
