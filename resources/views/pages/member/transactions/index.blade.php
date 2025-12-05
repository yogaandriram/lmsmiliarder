@extends('components.layout.member')
@section('page_title','Transaksi Saya')

@section('content')
@php
  $selected = isset($current) && $current ? $current : null;
  $status = $selected ? $selected->payment_status : null;
  $statusIcon = $status==='success' ? 'fa-solid fa-circle-check' : ($status==='failed' ? 'fa-solid fa-circle-xmark' : 'fa-solid fa-hourglass-half');
  $statusTitle = $status==='success' ? 'Pembayaran Berhasil' : ($status==='failed' ? 'Pembayaran Dibatalkan' : 'Menunggu Verifikasi');
  $statusDesc = $status==='success' ? 'Terima kasih, pembayaran Anda telah dikonfirmasi.' : ($status==='failed' ? 'Transaksi dibatalkan. Jika ini kesalahan, silakan lakukan checkout kembali.' : 'Pembayaran Anda sedang diproses oleh admin. Biasanya memakan waktu 1–2 jam kerja.');
  $first = $selected ? $selected->details->first() : null;
  $prodTitle = $first ? ($first->product_type==='course' ? ($first->course->title ?? 'Kursus') : ($first->ebook->title ?? 'E-book')) : '-';
  $prodType = $first ? ucfirst($first->product_type) : '-';
  $price = $first ? (int)$first->price : 0;
  $nominal = $selected ? (int)($selected->payable_amount ?? ($selected->final_amount ?? $selected->total_amount)) : 0;
@endphp

<div class="space-y-8">
  <div class="flex items-center justify-between">
    <h2 class="text-3xl md:text-4xl font-bold">Semua History Transaksi</h2>
  </div>

  @if($selected)
  <div class="glass p-6 rounded flex items-center gap-4">
    <div class="h-12 w-12 rounded-xl bg-linear-to-br from-yellow-500 to-orange-600 flex items-center justify-center shadow-md">
      <i class="{{ $statusIcon }} text-black/90"></i>
    </div>
    <div>
      <div class="text-lg font-semibold text-yellow-300">{{ $statusTitle }}</div>
      <div class="text-sm text-white/80">{{ $statusDesc }}</div>
    </div>
    
  </div>

  <div class="glass p-6 rounded">
    <h3 class="text-lg font-semibold text-yellow-400 mb-4">Detail</h3>
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-3 px-4">ID Transaksi</th>
          <th class="text-left py-3 px-4">Nama Kursus/E-book</th>
          <th class="text-left py-3 px-4">Tipe</th>
          <th class="text-right py-3 px-4">Harga</th>
        </tr>
      </x-slot>
      <tr class="border-b border-white/10">
        <td class="py-3 px-4">#{{ $selected->id }}</td>
        <td class="py-3 px-4">{{ $prodTitle }}</td>
        <td class="py-3 px-4">{{ $prodType }}</td>
        <td class="py-3 px-4 text-right">Rp {{ number_format($price,0,',','.') }}</td>
      </tr>
    </x-ui.table>
    @php $expires = $selected && $selected->expires_at ? $selected->expires_at : ($selected && optional($selected->transaction_time)?$selected->transaction_time->copy()->addHours(24):null); @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
      <div>Nominal: <strong>Rp {{ number_format($nominal,0,',','.') }}</strong></div>
      <div>Tanggal Pembayaran: <strong>{{ $selected ? optional($selected->transaction_time)->format('d M Y, H:i') : '-' }}</strong></div>
      <div>Berlaku Hingga: <strong>{{ $expires ? $expires->format('d M Y, H:i') : '-' }}</strong></div>
    </div>
  </div>
  @endif

  <h3 class="text-lg font-semibold text-yellow-400">Semua Transaksi</h3>
  <x-ui.table>
    <x-slot name="header">
      <tr>
        <th class="text-left py-3 px-4">ID</th>
        <th class="text-left py-3 px-4">Tanggal</th>
        <th class="text-left py-3 px-4">Item</th>
        <th class="text-right py-3 px-4">Total</th>
        <th class="text-center py-3 px-4">Status</th>
      </tr>
    </x-slot>
    @forelse($transactions as $t)
      <tr class="border-b border-white/10">
        <td class="py-3 px-4">#{{ $t->id }}</td>
        <td class="py-3 px-4">{{ optional($t->transaction_time)->format('d M Y H:i') ?? '-' }}</td>
        <td class="py-3 px-4">
          @foreach($t->details as $d)
            <div class="text-sm">{{ $d->product_type==='course' ? ($d->course->title ?? 'Kursus') : ($d->ebook->title ?? 'E-book') }}</div>
          @endforeach
        </td>
        <td class="py-3 px-4 text-right">Rp {{ number_format((int)($t->final_amount ?? $t->total_amount),0,',','.') }}</td>
        <td class="py-3 px-4 text-center">
          <span class="px-2 py-1 rounded-full text-xs 
            @if($t->payment_status==='success') bg-green-500/20 text-green-300 
            @elseif($t->payment_status==='failed') bg-red-500/20 text-red-300 
            @else bg-yellow-500/20 text-yellow-300 @endif">
            {{ ucfirst($t->payment_status) }}
          </span>
        </td>
        
      </tr>
    @empty
      <tr>
        <td colspan="5" class="py-8 px-4 text-center text-white/60">Belum ada transaksi.</td>
      </tr>
    @endforelse
  </x-ui.table>

  
</div>
@endsection
