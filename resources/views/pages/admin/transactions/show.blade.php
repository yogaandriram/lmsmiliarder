@extends('components.layout.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-semibold">Detail Transaksi #{{ $transaction->id }}</h2>
  <x-ui.btn-secondary href="{{ route('admin.transactions.index') }}" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
  </div>

<div class="space-y-6">
  <div class="glass p-6 rounded">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-sm text-white/70">User</div>
        <div class="font-semibold">{{ $transaction->user->name ?? 'User #'.$transaction->user_id }}</div>
      </div>
      <div>
        <span class="px-2 py-1 rounded-full text-xs 
          @if($transaction->payment_status==='success') bg-green-500/20 text-green-300 
          @elseif($transaction->payment_status==='failed') bg-red-500/20 text-red-300 
          @else bg-yellow-500/20 text-yellow-300 @endif">
          {{ ucfirst($transaction->payment_status) }}
        </span>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
      <div>Subtotal: <strong>Rp {{ number_format((int)$transaction->total_amount,0,',','.') }}</strong></div>
      <div>Diskon: <strong>Rp {{ number_format((int)$transaction->discount_amount,0,',','.') }}</strong></div>
      <div>Setelah Diskon: <strong>Rp {{ number_format((int)$transaction->final_amount,0,',','.') }}</strong></div>
      <div>Kode Unik: <strong>{{ (int)$transaction->unique_code }}</strong></div>
      <div>Nominal Transfer: <strong>Rp {{ number_format((int)$transaction->payable_amount,0,',','.') }}</strong></div>
      <div>Tanggal: <strong>{{ optional($transaction->transaction_time)->format('d M Y H:i') ?? '-' }}</strong></div>
    </div>
  </div>

  <div class="glass p-6 rounded">
    <div class="text-white/80 mb-2">Item</div>
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-2 px-3">Produk</th>
          <th class="text-left py-2 px-3">Tipe</th>
          <th class="text-right py-2 px-3">Harga</th>
          <th class="text-right py-2 px-3">Harga Efektif</th>
          <th class="text-right py-2 px-3">% Mentor</th>
          <th class="text-right py-2 px-3">Komisi Mentor</th>
          <th class="text-right py-2 px-3">Komisi Admin</th>
        </tr>
      </x-slot>
      @foreach($transaction->details as $d)
        <tr class="border-b border-white/10">
          <td class="py-2 px-3">{{ $d->product_type==='course' ? ($d->course->title ?? 'Kursus') : ($d->ebook->title ?? 'E-book') }}</td>
          <td class="py-2 px-3">{{ ucfirst($d->product_type) }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$d->price,0,',','.') }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$d->effective_price,0,',','.') }}</td>
          <td class="py-2 px-3 text-right">{{ (int)$d->mentor_share_percent }}%</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$d->mentor_earning,0,',','.') }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$d->admin_commission,0,',','.') }}</td>
        </tr>
      @endforeach
    </x-ui.table>
  </div>

  <div class="glass p-6 rounded">
    <div class="text-white/80 mb-2">Detail Transfer</div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>Nama Pengirim: <strong>{{ $transaction->sender_name ?? '-' }}</strong></div>
      <div>No. Rekening Pengirim: <strong>{{ $transaction->sender_account_no ?? '-' }}</strong></div>
      <div>Bank Asal: <strong>{{ $transaction->origin_bank ?? '-' }}</strong></div>
      <div>Bank Tujuan: <strong>{{ $transaction->destination_bank ?? ($transaction->adminBankAccount->bank_name ?? '-') }}</strong></div>
      <div>Nominal Transfer: <strong>Rp {{ number_format((int)($transaction->transfer_amount ?? $transaction->payable_amount),0,',','.') }}</strong></div>
      <div>Bukti Pembayaran: 
        @if($transaction->payment_proof_url)
          <a href="{{ $transaction->payment_proof_url }}" target="_blank" class="text-yellow-400 underline">Lihat</a>
        @else
          <span class="text-white/60">-</span>
        @endif
      </div>
    </div>
    <div class="mt-3">Catatan: <span class="text-white/80">{{ $transaction->transfer_note ?? '-' }}</span></div>
    @if($transaction->payment_status==='pending')
    @endif
  </div>

  @if($transaction->payment_status==='pending')
  <div class="flex items-center justify-between">
    <form method="POST" action="{{ route('admin.transactions.verify', $transaction) }}" class="flex">
      @csrf
      <input type="hidden" name="status" value="success">
      <x-ui.btn-primary type="submit" icon="fa-solid fa-check">Konfirmasi Pembayaran</x-ui.btn-primary>
    </form>
    <form method="POST" action="{{ route('admin.transactions.verify', $transaction) }}" class="flex">
      @csrf
      <input type="hidden" name="status" value="failed">
      <x-ui.btn-secondary type="submit" icon="fa-solid fa-xmark">Batalkan Pembayaran</x-ui.btn-secondary>
    </form>
  </div>
  @endif
</div>
@endsection
