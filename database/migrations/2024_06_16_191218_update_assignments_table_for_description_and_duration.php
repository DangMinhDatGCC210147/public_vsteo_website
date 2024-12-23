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
        Schema::table('assignments', function (Blueprint $table) {
            // Thay đổi kiểu của trường 'description' thành 'longtext'
            $table->longText('description')->change();

            // Thêm trường 'duration' để lưu thời gian làm bài (tính bằng phút)
            $table->integer('duration')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Trả lại kiểu dữ liệu ban đầu của trường 'description'
            $table->text('description')->change();

            // Xóa trường 'duration'
            $table->dropColumn('duration');
        });
    }
};
