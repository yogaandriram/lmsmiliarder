<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorCommissionPayout extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','mentor_bank_account_id','admin_bank_account_id','amount','admin_fee','status','requested_at','processed_at','note','proof_url'];

    protected $casts = [
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(MentorBankAccount::class, 'mentor_bank_account_id');
    }

    public function adminBankAccount()
    {
        return $this->belongsTo(\App\Models\AdminBankAccount::class, 'admin_bank_account_id');
    }
}
