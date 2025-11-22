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

    /**
     * Get the transaction that this detail belongs to.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the course that this detail belongs to (if product_type is course).
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the ebook that this detail belongs to (if product_type is ebook).
     */
    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }
}