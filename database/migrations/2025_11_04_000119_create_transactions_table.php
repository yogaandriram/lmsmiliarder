<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('admin_bank_account_id')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('payment_proof_url')->nullable();
            $table->enum('payment_status', ['pending','success','failed']);
            $table->timestamp('transaction_time');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('admin_bank_account_id')->references('id')->on('admin_bank_accounts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};