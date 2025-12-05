<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->index('author_id');
        });
        Schema::table('ebooks', function (Blueprint $table) {
            $table->index('author_id');
        });
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->index('transaction_id');
            $table->index(['product_type','course_id','ebook_id']);
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['author_id']);
        });
        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropIndex(['author_id']);
        });
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropIndex(['transaction_id']);
            $table->dropIndex(['product_type','course_id','ebook_id']);
        });
    }
};

