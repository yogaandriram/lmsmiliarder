<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Enrollment;
use App\Models\UserEbookLibrary;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function pending()
    {
        $transactions = Transaction::with('details')
            ->where('payment_status','pending')
            ->orderBy('created_at','desc')
            ->get();
        return view('pages.admin.transactions.pending', compact('transactions'));
    }

    public function verify(Transaction $transaction, Request $request)
    {
        $request->validate([
            'status' => ['required','in:success,failed'],
        ]);

        $transaction->update(['payment_status' => $request->input('status')]);

        if ($request->input('status') === 'success') {
            foreach ($transaction->details as $detail) {
                if ($detail->product_type === 'course' && $detail->course_id) {
                    Enrollment::firstOrCreate([
                        'user_id' => $transaction->user_id,
                        'course_id' => $detail->course_id,
                    ]);
                }
                if ($detail->product_type === 'ebook' && $detail->ebook_id) {
                    UserEbookLibrary::firstOrCreate([
                        'user_id' => $transaction->user_id,
                        'ebook_id' => $detail->ebook_id,
                    ]);
                }
            }
        }

        return redirect()->route('admin.transactions.pending')->with('success', 'Status transaksi diperbarui');
    }
}