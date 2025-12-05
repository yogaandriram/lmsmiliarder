<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'payment_status',
        'payment_proof_url',
        'coupon_id',
        'total_amount',
        'discount_amount',
        'final_amount',
        'unique_code',
        'payable_amount',
        'transaction_time',
        'admin_bank_account_id',
        'payment_method',
        'expires_at',
        'sender_name',
        'sender_account_no',
        'origin_bank',
        'destination_bank',
        'transfer_amount',
        'transfer_note',
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns this transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transaction details for this transaction.
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function adminBankAccount()
    {
        return $this->belongsTo(\App\Models\AdminBankAccount::class, 'admin_bank_account_id');
    }
}
