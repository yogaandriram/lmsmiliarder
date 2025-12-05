<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Coerce any non-multiple_choice to multiple_choice first
        DB::table('quiz_questions')->where('question_type','!=','multiple_choice')->update(['question_type' => 'multiple_choice']);
        // Alter enum to single value
        DB::statement("ALTER TABLE `quiz_questions` MODIFY `question_type` ENUM('multiple_choice') NOT NULL DEFAULT 'multiple_choice'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `quiz_questions` MODIFY `question_type` ENUM('multiple_choice','essay') NOT NULL DEFAULT 'multiple_choice'");
    }
};

