<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;

class CommissionService
{
    public function applyForTransaction(Transaction $transaction): void
    {
        $transaction->loadMissing('details.course','details.ebook');
        foreach ($transaction->details as $detail) {
            $effective = (float)$detail->effective_price;
            if ($effective <= 0) {
                $detail->mentor_earning = 0.0;
                $detail->admin_commission = 0.0;
                $detail->save();
                continue;
            }
            $percent = (int)$detail->mentor_share_percent;
            $mentor = (float)number_format(($effective * $percent) / 100, 2, '.', '');
            $admin = (float)number_format($effective - $mentor, 2, '.', '');
            $detail->mentor_earning = $mentor;
            $detail->admin_commission = $admin;
            $detail->save();
        }
    }
}

