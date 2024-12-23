<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_answers', function (Blueprint $table) {
            $table->integer('attempt_number')->default(1);
        });
    }

    public function down()
    {
        Schema::table('student_answers', function (Blueprint $table) {
            $table->dropColumn('attempt_number');
        });
    }
};
