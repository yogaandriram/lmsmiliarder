<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id','product_type','course_id','ebook_id','price','quantity','discount_amount'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}