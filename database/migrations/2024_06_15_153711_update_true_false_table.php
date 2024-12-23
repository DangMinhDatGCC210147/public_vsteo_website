<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('true_false', function (Blueprint $table) {
            $table->dropColumn('correct_answer'); // Xóa cột hiện tại
        });

        Schema::table('true_false', function (Blueprint $table) {
            $table->enum('correct_answer', ['true', 'false', 'not_given'])->after('question_id'); // Tạo lại cột với kiểu enum
        });
    }

    public function down()
    {
        Schema::table('true_false', function (Blueprint $table) {
            $table->dropColumn('correct_answer'); // Xóa cột mới
        });

        Schema::table('true_false', function (Blueprint $table) {
            $table->boolean('correct_answer')->after('question_id'); // Khôi phục lại cột cũ
        });
    }
};
