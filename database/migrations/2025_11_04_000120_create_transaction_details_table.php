<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transaction_id');
            $table->enum('product_type', ['course','ebook']);
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('ebook_id')->nullable();
            $table->decimal('price', 10, 2);

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('ebook_id')->references('id')->on('ebooks');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};