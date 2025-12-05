@extends('components.layout.admin')
@section('page_title','Detail Komisi Mentor')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Detail Komisi • {{ $mentor->name }}</h2>
    <a href="{{ route('admin.commissions.index', ['date_start' => $start, 'date_end' => $end]) }}" class="px-3 py-2 rounded bg-white/10 text-white/80 hover:bg-white/20">Kembali</a>
  </div>

  <div class="flex justify-start">
    <x-ui.date-range-dropdown :action="route('admin.commissions.show', $mentor)" />
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <x-ui.stat-card label="Total Komisi Mentor" :value="'Rp '.number_format((int)$totalMentor,0,',','.')" icon="fa-solid fa-hand-holding-dollar" />
    <x-ui.stat-card label="Komisi Admin (efektif)" :value="'Rp '.number_format((int)$totalAdmin,0,',','.')" icon="fa-solid fa-scale-balanced" />
    <x-ui.stat-card label="Komisi Sudah Cair" :value="'Rp '.number_format((int)$paid,0,',','.')" icon="fa-solid fa-circle-check" />
    <x-ui.stat-card label="Komisi Belum Cair" :value="'Rp '.number_format((int)$available,0,',','.')" icon="fa-solid fa-hourglass-end" />
  </div>

  <div class="glass p-6 rounded">
    <div class="text-white/80 mb-2">Rincian Transaksi Produk</div>
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
          <th class="text-right py-2 px-3">Komisi Admin</th>
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
          <td class="py-2 px-3 text-right text-yellow-300">Rp {{ number_format((int)$d->mentor_earning,0,',','.') }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$d->admin_commission,0,',','.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="py-4 px-3 text-white/70">Tidak ada transaksi pada rentang ini.</td>
        </tr>
      @endforelse
    </x-ui.table>

    <div class="mt-4">
      <x-ui.pagination :paginator="$details" />
    </div>
  </div>
</div>
@endsection
