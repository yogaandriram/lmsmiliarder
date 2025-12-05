<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->decimal('mentor_earning', 12, 2)->nullable()->after('price');
            $table->decimal('admin_commission', 12, 2)->nullable()->after('mentor_earning');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn(['mentor_earning','admin_commission']);
        });
    }
};

