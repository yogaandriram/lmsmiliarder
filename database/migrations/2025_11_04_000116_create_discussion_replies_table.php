<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discussion_replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('parent_reply_id')->nullable();
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('thread_id')->references('id')->on('discussion_threads')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('parent_reply_id')->references('id')->on('discussion_replies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discussion_replies');
    }
};