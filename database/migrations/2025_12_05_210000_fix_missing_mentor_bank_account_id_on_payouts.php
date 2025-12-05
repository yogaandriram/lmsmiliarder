<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('mentor_commission_payouts', 'mentor_bank_account_id')) {
            Schema::table('mentor_commission_payouts', function (Blueprint $table) {
                $table->unsignedBigInteger('mentor_bank_account_id')->nullable()->after('user_id');
                $table->foreign('mentor_bank_account_id')->references('id')->on('mentor_bank_accounts')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('mentor_commission_payouts', 'mentor_bank_account_id')) {
            Schema::table('mentor_commission_payouts', function (Blueprint $table) {
                $table->dropForeign(['mentor_bank_account_id']);
                $table->dropColumn('mentor_bank_account_id');
            });
        }
    }
};

