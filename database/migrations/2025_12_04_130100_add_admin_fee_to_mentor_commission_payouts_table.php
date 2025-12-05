<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mentor_commission_payouts', function (Blueprint $table) {
            $table->decimal('admin_fee', 12, 2)->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('mentor_commission_payouts', function (Blueprint $table) {
            $table->dropColumn('admin_fee');
        });
    }
};

