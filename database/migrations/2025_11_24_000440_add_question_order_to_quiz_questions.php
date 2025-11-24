<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('quiz_questions', 'question_order')) {
            Schema::table('quiz_questions', function (Blueprint $table) {
                $table->integer('question_order')->default(0);
            });
            if (Schema::hasColumn('quiz_questions', 'order')) {
                DB::statement('UPDATE `quiz_questions` SET `question_order` = `order`');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('quiz_questions', 'question_order')) {
            Schema::table('quiz_questions', function (Blueprint $table) {
                $table->dropColumn('question_order');
            });
        }
    }
};