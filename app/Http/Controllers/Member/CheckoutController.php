<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Coupon;
use App\Models\Enrollment;
use App\Models\UserEbookLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Services\CommissionService;

class CheckoutController extends Controller
{
    private function ensureOwned(Transaction $transaction): void
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
    }

    public function course(Course $course)
    {
        return view('pages.public.checkout.course', compact('course'));
    }

    public function ebook(Ebook $ebook)
    {
        return view('pages.public.checkout.ebook', compact('ebook'));
    }

    public function purchaseCourse(Request $request, Course $course)
    {
        $price = max(0, (int)($course->price ?? 0));
        $code = trim((string)$request->input('coupon_code'));
        $discount = 0.0;
        $couponId = null;
        if ($code !== '') {
            $coupon = Coupon::where('code', $code)
                ->where(function($q){ $q->where('is_active', true)->orWhereNull('is_active'); })
                ->first();
            if ($coupon && (!$coupon->expires_at || now()->lte($coupon->expires_at))) {
                $used = Transaction::where('coupon_id', $coupon->id)->count();
                if (is_null($coupon->usage_limit) || $used < (int)$coupon->usage_limit) {
                    if ($coupon->discount_type === 'percentage') {
                        $discount = min($price, ($price * (float)$coupon->discount_value) / 100.0);
                    } else {
                        $discount = min($price, (float)$coupon->discount_value);
                    }
                    $couponId = $coupon->id;
                }
            }
        }
        $bankId = (int)($request->input('bank_id') ?? 0);
        $final = max(0, $price - $discount);
        $unique = $final > 0 ? random_int(1, 999) : 0;
        $payable = max(0, $final - $unique);
        if ($final > 0 && $bankId <= 0) {
            return back()->with('error', 'Pilih rekening tujuan pembayaran terlebih dahulu.');
        }
        $tx = Transaction::create([
            'user_id' => Auth::id(),
            'payment_status' => $final <= 0 ? 'success' : 'pending',
            'total_amount' => $price,
            'coupon_id' => $couponId,
            'discount_amount' => $discount,
            'final_amount' => $final,
            'transaction_time' => Carbon::now(),
            'admin_bank_account_id' => $bankId > 0 ? $bankId : null,
            'unique_code' => $unique,
            'payable_amount' => $payable,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        TransactionDetail::create([
            'transaction_id' => $tx->id,
            'product_type' => 'course',
            'course_id' => $course->id,
            'price' => $price,
        ]);

        if ($tx->payment_status === 'success') {
            app(CommissionService::class)->applyForTransaction($tx);
            Enrollment::firstOrCreate([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
            ], [
                'enrolled_at' => Carbon::now(),
            ]);
            return redirect()->route('member.transactions.index')->with('success', 'Kursus gratis ditambahkan ke akun Anda.');
        }
        return redirect()->route('checkout.payments.show', $tx);
    }

    public function purchaseEbook(Request $request, Ebook $ebook)
    {
        $price = max(0, (int)($ebook->price ?? 0));
        $code = trim((string)$request->input('coupon_code'));
        $discount = 0.0;
        $couponId = null;
        if ($code !== '') {
            $coupon = Coupon::where('code', $code)
                ->where(function($q){ $q->where('is_active', true)->orWhereNull('is_active'); })
                ->first();
            if ($coupon && (!$coupon->expires_at || now()->lte($coupon->expires_at))) {
                $used = Transaction::where('coupon_id', $coupon->id)->count();
                if (is_null($coupon->usage_limit) || $used < (int)$coupon->usage_limit) {
                    if ($coupon->discount_type === 'percentage') {
                        $discount = min($price, ($price * (float)$coupon->discount_value) / 100.0);
                    } else {
                        $discount = min($price, (float)$coupon->discount_value);
                    }
                    $couponId = $coupon->id;
                }
            }
        }
        $bankId = (int)($request->input('bank_id') ?? 0);
        $final = max(0, $price - $discount);
        $unique = $final > 0 ? random_int(1, 999) : 0;
        $payable = max(0, $final - $unique);
        if ($final > 0 && $bankId <= 0) {
            return back()->with('error', 'Pilih rekening tujuan pembayaran terlebih dahulu.');
        }
        $tx = Transaction::create([
            'user_id' => Auth::id(),
            'payment_status' => $final <= 0 ? 'success' : 'pending',
            'total_amount' => $price,
            'coupon_id' => $couponId,
            'discount_amount' => $discount,
            'final_amount' => $final,
            'transaction_time' => Carbon::now(),
            'admin_bank_account_id' => $bankId > 0 ? $bankId : null,
            'unique_code' => $unique,
            'payable_amount' => $payable,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        TransactionDetail::create([
            'transaction_id' => $tx->id,
            'product_type' => 'ebook',
            'ebook_id' => $ebook->id,
            'price' => $price,
        ]);

        if ($tx->payment_status === 'success') {
            app(CommissionService::class)->applyForTransaction($tx);
            UserEbookLibrary::firstOrCreate([
                'user_id' => Auth::id(),
                'ebook_id' => $ebook->id,
            ], [
                'purchased_at' => Carbon::now(),
            ]);
            return redirect()->route('member.transactions.index')->with('success', 'E-book gratis ditambahkan ke akun Anda.');
        }
        return redirect()->route('checkout.payments.show', $tx);
    }

    public function showPayment(Transaction $transaction)
    {
        $this->ensureOwned($transaction);
        // Auto-cancel if expired and still pending
        if ($transaction->payment_status === 'pending' && $transaction->expires_at && now()->gt($transaction->expires_at)) {
            $transaction->payment_status = 'failed';
            $transaction->save();
        }
        $transaction->load('details.course','details.ebook');
        return view('pages.member.payments.show', compact('transaction'));
    }

    public function uploadPaymentProof(Request $request, Transaction $transaction)
    {
        $this->ensureOwned($transaction);
        $data = $request->validate([
            'payment_method' => ['nullable','string'],
            'payment_proof' => ['required','file','mimes:jpg,jpeg,png,pdf','max:4096'],
            'sender_name' => ['nullable','string','max:255'],
            'sender_account_no' => ['nullable','string','max:255'],
            'origin_bank' => ['nullable','string','max:255'],
            'destination_bank' => ['nullable','string','max:255'],
            'transfer_amount' => ['nullable','integer','min:0'],
            'transfer_note' => ['nullable','string'],
        ]);
        $file = $request->file('payment_proof');
        $path = $file->store('payment_proofs/'.Auth::id(), 'public');
        $transaction->payment_method = $data['payment_method'] ?? null;
        $transaction->payment_proof_url = Storage::url($path);
        $transaction->payment_status = 'pending';
        $transaction->transaction_time = Carbon::now();
        $transaction->sender_name = $data['sender_name'] ?? null;
        $transaction->sender_account_no = $data['sender_account_no'] ?? null;
        $transaction->origin_bank = $data['origin_bank'] ?? null;
        $transaction->destination_bank = $data['destination_bank'] ?? null;
        $transaction->transfer_amount = $data['transfer_amount'] ?? null;
        $transaction->transfer_note = $data['transfer_note'] ?? null;
        $transaction->save();

        return redirect()->route('checkout.transactions.show', $transaction)->with('success', 'Bukti pembayaran diunggah. Menunggu verifikasi admin.');
    }
}
