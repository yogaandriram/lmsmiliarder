<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'unique_code')) {
                $table->unsignedSmallInteger('unique_code')->default(0);
            }
            if (!Schema::hasColumn('transactions', 'payable_amount')) {
                $table->unsignedInteger('payable_amount')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'unique_code')) {
                $table->dropColumn('unique_code');
            }
            if (Schema::hasColumn('transactions', 'payable_amount')) {
                $table->dropColumn('payable_amount');
            }
        });
    }
};

