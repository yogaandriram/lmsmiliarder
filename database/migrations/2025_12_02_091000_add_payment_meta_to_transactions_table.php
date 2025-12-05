<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'expires_at')) {
                $table->timestamp('expires_at')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'sender_name')) {
                $table->string('sender_name')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'sender_account_no')) {
                $table->string('sender_account_no')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'origin_bank')) {
                $table->string('origin_bank')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'destination_bank')) {
                $table->string('destination_bank')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'transfer_amount')) {
                $table->unsignedInteger('transfer_amount')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'transfer_note')) {
                $table->text('transfer_note')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['expires_at','sender_name','sender_account_no','origin_bank','destination_bank','transfer_amount','transfer_note'] as $col) {
                if (Schema::hasColumn('transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

