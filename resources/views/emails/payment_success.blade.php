<div style="font-family: Arial, sans-serif; color:#111">
  <h2>Pembayaran Berhasil</h2>
  <p>Halo {{ $transaction->user->name ?? 'Member' }},</p>
  <p>Terima kasih. Pembayaran untuk transaksi #{{ $transaction->id }} telah berhasil dikonfirmasi.</p>
  <p><strong>Nominal Transfer:</strong> Rp {{ number_format((int)$transaction->payable_amount,0,',','.') }}</p>
  <p><strong>Rincian Item:</strong></p>
  <ul>
    @foreach($transaction->details as $d)
      <li>{{ $d->product_type==='course' ? ($d->course->title ?? 'Kursus') : ($d->ebook->title ?? 'E-book') }} — Rp {{ number_format((int)$d->price,0,',','.') }}</li>
    @endforeach
  </ul>
  <p>Selamat belajar di EduLux!</p>
</div>

