<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Rename column `order` to `question_order` to avoid reserved keyword conflicts
        Schema::table('quiz_questions', function (Blueprint $table) {
            // MySQL requires raw statement for renaming when using reserved words
        });
        DB::statement('ALTER TABLE `quiz_questions` CHANGE `order` `question_order` INT');
    }

    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
        });
        DB::statement('ALTER TABLE `quiz_questions` CHANGE `question_order` `order` INT');
    }
};