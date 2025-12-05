<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mentor_commission_payouts', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_bank_account_id')->nullable();
            $table->foreign('admin_bank_account_id')->references('id')->on('admin_bank_accounts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mentor_commission_payouts', function (Blueprint $table) {
            $table->dropForeign(['admin_bank_account_id']);
            $table->dropColumn('admin_bank_account_id');
        });
    }
};
