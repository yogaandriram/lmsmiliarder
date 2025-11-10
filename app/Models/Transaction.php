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

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}