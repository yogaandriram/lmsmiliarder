<div style="font-family: Arial, sans-serif; color:#111">
  <h2>Pembayaran Dibatalkan</h2>
  <p>Halo {{ $transaction->user->name ?? 'Member' }},</p>
  <p>Transaksi #{{ $transaction->id }} telah dibatalkan. Jika ini kesalahan, silakan lakukan checkout kembali dan unggah bukti pembayaran.</p>
  <p>Terima kasih.</p>
</div>

