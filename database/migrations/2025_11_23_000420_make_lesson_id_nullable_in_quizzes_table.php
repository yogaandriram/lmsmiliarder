<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
        });

        DB::statement('ALTER TABLE quizzes MODIFY lesson_id BIGINT UNSIGNED NULL');

        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
        });

        DB::statement('ALTER TABLE quizzes MODIFY lesson_id BIGINT UNSIGNED NOT NULL');

        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }
};