<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $now = \Illuminate\Support\Carbon::now();
            \App\Models\Transaction::where('payment_status','pending')
                ->where(function($q) use ($now) {
                    $q->whereNotNull('expires_at')->where('expires_at','<',$now)
                      ->orWhere(function($qq) use ($now) {
                          $qq->whereNull('expires_at')->where('transaction_time','<', $now->copy()->subHours(72));
                      });
                })
                ->orderBy('id')
                ->chunkById(200, function($rows){
                    foreach ($rows as $tx) {
                        $tx->payment_status = 'failed';
                        $tx->save();
                        try {
                            \App\Models\Notification::create([
                                'id' => (string) \Illuminate\Support\Str::uuid(),
                                'recipient_id' => $tx->user_id,
                                'message' => 'Transaksi #'.$tx->id.' dibatalkan karena melewati batas waktu 72 jam tanpa pembayaran.',
                                'link_url' => route('checkout.transactions.show', $tx),
                                'created_at' => now(),
                            ]);
                        } catch (\Throwable $e) {}
                    }
                }, $column='id');
        } catch (\Throwable $e) {
            // silent
        }
    }
}
