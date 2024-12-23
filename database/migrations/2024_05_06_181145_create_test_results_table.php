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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('test_name');
            $table->integer('listening_correctness');
            $table->integer('reading_correctness');
            $table->decimal('writing_part1', 3, 1)->nullable();
            $table->decimal('writing_part2', 3, 1)->nullable();
            $table->decimal('speaking_part1', 3, 1)->nullable();
            $table->decimal('speaking_part2', 3, 1)->nullable();
            $table->decimal('speaking_part3', 3, 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
