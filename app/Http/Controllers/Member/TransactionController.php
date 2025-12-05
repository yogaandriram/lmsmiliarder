<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('details.course','details.ebook')
            ->orderByDesc('transaction_time')
            ->get();
        return view('pages.member.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) abort(403);
        $transaction->load('details.course','details.ebook');
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('details.course','details.ebook')
            ->orderByDesc('transaction_time')
            ->get();
        return view('pages.checkout.transactions.show', ['transactions' => $transactions, 'current' => $transaction]);
    }
}
