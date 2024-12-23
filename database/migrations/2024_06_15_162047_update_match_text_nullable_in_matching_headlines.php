<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matching_headlines', function (Blueprint $table) {
            $table->string('match_text')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('matching_headlines', function (Blueprint $table) {
            $table->string('match_text')->nullable(false)->change();
        });
    }
};
