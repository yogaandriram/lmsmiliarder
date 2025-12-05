<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('subscription_type')->default('lifetime');
            $table->date('subscription_start_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->unsignedInteger('subscription_duration_value')->nullable();
            $table->string('subscription_duration_unit')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_type',
                'subscription_start_date',
                'subscription_end_date',
                'subscription_duration_value',
                'subscription_duration_unit',
            ]);
        });
    }
};

