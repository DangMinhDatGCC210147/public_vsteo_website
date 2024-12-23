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
        Schema::create('matching_headlines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('question_homeworks')->onDelete('cascade');
            $table->string('headline');
            $table->string('match_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matching_headlines');
    }
};
