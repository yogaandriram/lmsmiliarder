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

    public function getMentorSharePercentAttribute(): int
    {
        if ($this->product_type === 'course' && $this->course) {
            return (int)($this->course->mentor_share_percent ?? 80);
        }
        return 0;
    }

    public function getAdminSharePercentAttribute(): int
    {
        $m = $this->mentor_share_percent;
        return max(0, 100 - (int)$m);
    }

    public function getEffectivePriceAttribute(): float
    {
        $base = (float)$this->price;
        if ($base <= 0) return 0.0;
        $tx = $this->relationLoaded('transaction') ? $this->transaction : $this->transaction()->with('details')->first();
        if (!$tx || (float)$tx->discount_amount <= 0) return $base;
        $sum = (float)$tx->details->sum('price');
        if ($sum <= 0) return $base;
        $ratio = $base / $sum;
        $discountShare = (float)$tx->discount_amount * $ratio;
        $effective = $base - $discountShare;
        return $effective > 0 ? (float)number_format($effective, 2, '.', '') : 0.0;
    }

    public function getMentorEarningAttribute(): float
    {
        if ($this->product_type !== 'course') return 0.0;
        $effective = $this->effective_price;
        $percent = (int)$this->mentor_share_percent;
        return (float)number_format(($effective * $percent) / 100, 2, '.', '');
    }

    public function getAdminCommissionAttribute(): float
    {
        if ($this->product_type !== 'course') return 0.0;
        $effective = $this->effective_price;
        return (float)number_format($effective - $this->mentor_earning, 2, '.', '');
    }
}