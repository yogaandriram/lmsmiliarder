<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Enrollment;
use App\Models\UserEbookLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccessMail;
use App\Mail\PaymentCancelledMail;
use App\Services\CommissionService;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user','details.course','details.ebook','adminBankAccount')
            ->orderByDesc('transaction_time')
            ->paginate(15);
        return view('pages.admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user','details.course','details.ebook','adminBankAccount');
        return view('pages.admin.transactions.show', compact('transaction'));
    }
    public function pending()
    {
        $transactions = Transaction::with('details.course','details.ebook')
            ->where('payment_status','pending')
            ->orderBy('transaction_time','desc')
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
                    ], [
                        'enrolled_at' => now(),
                    ]);
                }
                if ($detail->product_type === 'ebook' && $detail->ebook_id) {
                    UserEbookLibrary::firstOrCreate([
                        'user_id' => $transaction->user_id,
                        'ebook_id' => $detail->ebook_id,
                    ], [
                        'purchased_at' => now(),
                    ]);
                }
            }

            app(CommissionService::class)->applyForTransaction($transaction);

            $transaction->load('user','details.course','details.ebook');
            if ($transaction->user && $transaction->user->email) {
                try { Mail::to($transaction->user->email)->send(new PaymentSuccessMail($transaction)); } catch (\Throwable $e) {}
            }
        } elseif ($request->input('status') === 'failed') {
            $transaction->load('user');
            if ($transaction->user && $transaction->user->email) {
                try { Mail::to($transaction->user->email)->send(new PaymentCancelledMail($transaction)); } catch (\Throwable $e) {}
            }
        }

        return redirect()->route('admin.transactions.pending')->with('success', 'Status transaksi diperbarui');
    }
}
