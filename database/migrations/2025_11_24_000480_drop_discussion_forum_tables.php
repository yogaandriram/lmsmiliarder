<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop forum tables if they still exist
        DB::statement('DROP TABLE IF EXISTS `discussion_replies`');
        DB::statement('DROP TABLE IF EXISTS `discussion_threads`');
    }

    public function down(): void
    {
        // No need to recreate legacy forum tables
    }
};