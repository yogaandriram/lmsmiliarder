@extends('components.layout.mentor')
@section('page_title','Penjualan')

@section('content')
<div class="space-y-6">
  <h2 class="text-2xl font-semibold">Penjualan</h2>

  <div class="glass p-4 rounded">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="p-4 rounded bg-white/5">
        <div class="text-white/60 text-sm">Total Item Terjual</div>
        <div class="text-xl font-semibold">{{ (int)$totalItems }}</div>
      </div>
      <div class="p-4 rounded bg-white/5">
        <div class="text-white/60 text-sm">Pendapatan Katalog</div>
        <div class="text-xl font-semibold">Rp {{ number_format((int)$totalGross,0,',','.') }}</div>
      </div>
      <div class="p-4 rounded bg-white/5">
        <div class="text-white/60 text-sm">Pendapatan Efektif</div>
        <div class="text-xl font-semibold">Rp {{ number_format((int)$totalEffective,0,',','.') }}</div>
      </div>
      <div class="p-4 rounded bg-white/5">
        <div class="text-white/60 text-sm">Komisi Mentor</div>
        <div class="text-xl font-semibold text-yellow-300">Rp {{ number_format((int)$totalMentor,0,',','.') }}</div>
      </div>
    </div>
  </div>

  <div class="glass p-6 rounded">
    <div class="text-white/80 mb-2">Rincian Penjualan</div>
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-2 px-3">Tanggal</th>
          <th class="text-left py-2 px-3">Produk</th>
          <th class="text-left py-2 px-3">Tipe</th>
          <th class="text-right py-2 px-3">Harga</th>
          <th class="text-right py-2 px-3">Harga Efektif</th>
          <th class="text-right py-2 px-3">% Mentor</th>
          <th class="text-right py-2 px-3">Komisi Mentor</th>
        </tr>
      </x-slot>
      @forelse($details as $d)
        <tr class="border-b border-white/10">
          <td class="py-2 px-3">{{ optional($d->transaction->transaction_time)->format('d M Y H:i') ?? '-' }}</td>
          <td class="py-2 px-3">{{ $d->product_type==='course' ? ($d->course->title ?? 'Kursus') : ($d->ebook->title ?? 'E-book') }}</td>
          <td class="py-2 px-3">{{ ucfirst($d->product_type) }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$d->price,0,',','.') }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$d->effective_price,0,',','.') }}</td>
          <td class="py-2 px-3 text-right">{{ (int)$d->mentor_share_percent }}%</td>
          <td class="py-2 px-3 text-right text-yellow-400">Rp {{ number_format((int)$d->mentor_earning,0,',','.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="py-4 px-3 text-white/70">Belum ada penjualan.</td>
        </tr>
      @endforelse
    </x-ui.table>

    <div class="mt-4">
      <x-ui.pagination :paginator="$details" />
    </div>
  </div>
</div>
@endsection

