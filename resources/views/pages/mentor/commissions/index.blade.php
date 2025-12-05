@extends('components.layout.mentor')
@section('page_title','Komisi Saya')

@section('content')
<div class="space-y-6">
  <h2 class="text-2xl font-semibold">Komisi Saya</h2>

  <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
    <div class="md:col-span-3 space-y-4">
      <div class="p-4 rounded glass">
        <div class="text-white/60 text-sm">Total Komisi</div>
        <div class="text-xl font-semibold text-yellow-400">Rp {{ number_format((int)$totalMentor,0,',','.') }}</div>
      </div>
      <div class="p-4 rounded glass">
        <div class="text-white/60 text-sm">Komisi Sudah Cair</div>
        <div class="text-xl font-semibold">Rp {{ number_format((int)$paid,0,',','.') }}</div>
      </div>
      <div class="p-4 rounded glass">
        <div>
          <div class="text-white/60 text-sm">Komisi Belum Cair</div>
          <div class="text-xl font-semibold">Rp {{ number_format((int)$available,0,',','.') }}</div>
        </div>
        <div class="mt-2 text-white/50 text-xs">Dalam proses: Rp {{ number_format((int)$pending,0,',','.') }}</div>
        <form method="POST" action="{{ route('mentor.commissions.request_payout') }}" class="mt-3">
          @csrf
          <x-ui.btn-primary type="submit" :disabled="$available<=0" icon="fa-solid fa-hand-holding-dollar">Request Cair</x-ui.btn-primary>
        </form>
      </div>
    </div>
    <div class="md:col-span-9 glass p-4 rounded">
      <x-ui.chart-barline :labels="$chart['labels']" :bars="$chart['bars']" :line="$chart['line']" />
      <div class="mt-2 text-white/60 text-xs">Count: {{ (int)$totalItems }}</div>
    </div>
  </div>

  <div class="flex justify-start">
    <x-ui.date-range-dropdown />
  </div>

  <div class="glass p-6 rounded">
    <div class="text-white/80 mb-2">Rincian Komisi</div>
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
          <td class="py-2 px-3 text-right text-yellow-400">Rp {{ number_format((int)$d->mentor_earning,0,',','.') }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$d->admin_commission,0,',','.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="py-4 px-3 text-white/70">Belum ada komisi.</td>
        </tr>
      @endforelse
    </x-ui.table>

    <div class="mt-4">
      <x-ui.pagination :paginator="$details" />
    </div>
  </div>

  <div class="glass p-6 rounded">
    <div class="flex items-center justify-between mb-2">
      <div class="text-white/80">Riwayat Pencairan</div>
      <div class="text-white/50 text-sm">Menampilkan terbaru</div>
    </div>
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-2 px-3">Diajukan</th>
          <th class="text-left py-2 px-3">Diproses</th>
          <th class="text-left py-2 px-3">Status</th>
          <th class="text-right py-2 px-3">Jumlah</th>
          <th class="text-left py-2 px-3">Rekening</th>
          <th class="text-left py-2 px-3">Bukti</th>
        </tr>
      </x-slot>
      @forelse($payouts as $p)
        <tr class="border-b border-white/10">
          <td class="py-2 px-3">{{ optional($p->requested_at)->format('d M Y H:i') ?? '-' }}</td>
          <td class="py-2 px-3">{{ optional($p->processed_at)->format('d M Y H:i') ?? '-' }}</td>
          <td class="py-2 px-3">
            <span class="px-2 py-1 rounded-full text-xs @if($p->status==='approved') bg-green-500/20 text-green-300 @elseif($p->status==='rejected') bg-red-500/20 text-red-300 @else bg-yellow-500/20 text-yellow-300 @endif">{{ ucfirst($p->status) }}</span>
          </td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$p->amount,0,',','.') }}</td>
          <td class="py-2 px-3">{{ $p->bankAccount->bank_name ?? '-' }} • {{ $p->bankAccount ? $p->bankAccount->account_number : '-' }}</td>
          <td class="py-2 px-3">
            @if($p->proof_url)
              <a href="{{ $p->proof_url }}" target="_blank" class="text-yellow-300 underline">Lihat</a>
            @else
              <span class="text-white/60">-</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="py-4 px-3 text-white/70">Belum ada riwayat pencairan.</td>
        </tr>
      @endforelse
    </x-ui.table>

    <div class="mt-4">
      <x-ui.pagination :paginator="$payouts" />
    </div>
  </div>
</div>
@endsection
