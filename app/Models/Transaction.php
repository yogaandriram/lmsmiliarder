<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','payment_status','payment_proof_url','coupon_id','total_amount'
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
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
}