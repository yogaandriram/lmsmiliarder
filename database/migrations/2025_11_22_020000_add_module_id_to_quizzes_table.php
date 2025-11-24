<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable()->after('lesson_id');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->unique('module_id');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropUnique(['module_id']);
            $table->dropForeign(['module_id']);
            $table->dropColumn('module_id');
        });
    }
};