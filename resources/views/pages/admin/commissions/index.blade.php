@extends('components.layout.admin')
@section('page_title','Komisi Mentor')

@section('content')
<div class="space-y-6">
  <h2 class="text-2xl font-semibold">Komisi Mentor</h2>

  <div class="flex justify-start">
    <x-ui.date-range-dropdown :action="route('admin.commissions.index')" />
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <x-ui.stat-card label="Komisi Sudah Cair" :value="'Rp '.number_format((int)($totalApproved ?? 0),0,',','.')" icon="fa-solid fa-circle-check" />
    <x-ui.stat-card label="Komisi Dalam Proses" :value="'Rp '.number_format((int)($totalPending ?? 0),0,',','.')" icon="fa-solid fa-hourglass-half" />
    <x-ui.stat-card label="Komisi Belum Cair" :value="'Rp '.number_format((int)($totalAvailable ?? 0),0,',','.')" icon="fa-solid fa-hand-holding-dollar" />
  </div>

  <div class="glass p-6 rounded">
    <div class="text-white/80 mb-2">Ringkasan per Mentor</div>
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-2 px-3">Mentor</th>
          <th class="text-left py-2 px-3">Email</th>
          <th class="text-right py-2 px-3">Total Komisi</th>
          <th class="text-right py-2 px-3">Sudah Cair</th>
          <th class="text-right py-2 px-3">Dalam Proses</th>
          <th class="text-right py-2 px-3">Belum Cair</th>
          <th class="text-left py-2 px-3">Rekening Default</th>
          <th class="text-left py-2 px-3">Aksi</th>
        </tr>
      </x-slot>
      @forelse($rows as $r)
        <tr class="border-b border-white/10">
          <td class="py-2 px-3">{{ $r['user']->name ?? 'Mentor #'.($r['user']->id ?? '-') }}</td>
          <td class="py-2 px-3">{{ $r['user']->email ?? '-' }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$r['total'],0,',','.') }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$r['approved'],0,',','.') }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$r['pending'],0,',','.') }}</td>
          <td class="py-2 px-3 text-right">Rp {{ number_format((int)$r['available'],0,',','.') }}</td>
          <td class="py-2 px-3">{{ $r['bank']->bank_name ?? '-' }} • {{ $r['bank']->account_number ?? '-' }}</td>
          <td class="py-2 px-3">
            @if(isset($r['user']))
              <div class="flex items-center gap-2">
                <a href="{{ route('admin.commissions.show', ['mentor' => $r['user']->id, 'date_start' => $start, 'date_end' => $end]) }}" class="px-3 py-2 rounded bg-white/10 text-white/80 hover:bg-white/20" title="Detail"><i class="fa-solid fa-eye"></i></a>
                <button type="button" class="px-3 py-2 rounded bg-white/10 text-white/80 hover:bg-white/20" title="Cairkan" data-toggle-modal data-target="modalPayout{{ $r['user']->id }}"><i class="fa-solid fa-money-bill-wave"></i></button>
              </div>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="py-4 px-3 text-white/70">Tidak ada data pada rentang tanggal ini.</td>
        </tr>
      @endforelse
    </x-ui.table>
  </div>
  @foreach($rows as $r)
    @if(isset($r['user']))
    <div id="modalPayout{{ $r['user']->id }}" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
      <div class="glass p-6 rounded w-full max-w-md">
        <h4 class="text-lg font-semibold mb-4">Cairkan Komisi • {{ $r['user']->name }}</h4>
        <form method="POST" action="{{ route('admin.commissions.payout', $r['user']->id) }}" class="space-y-3">
          @csrf
          <input type="hidden" name="date_start" value="{{ $start }}" />
          <input type="hidden" name="date_end" value="{{ $end }}" />
          <label class="block text-sm font-medium text-white/90">Nominal</label>
          <div class="flex items-center gap-2">
            <input id="payoutAmount{{ $r['user']->id }}" name="amount" type="number" step="0.01" value="{{ $r['available'] }}" required class="flex-1 p-2 rounded bg-white/10 border border-white/20" />
            <button type="button" class="px-3 py-2 rounded bg-white/10 text-white/80 hover:bg-white/20" title="Salin Nominal" data-copy-input="payoutAmount{{ $r['user']->id }}"><i class="fa-regular fa-copy"></i></button>
          </div>
          <label class="block text-sm font-medium text-white/90 mt-3">Rekening Mentor (Tujuan)</label>
          <div class="flex items-center justify-between p-2 rounded bg-white/10 border border-white/20">
            <div class="text-white/90">{{ $r['bank']->bank_name ?? '-' }} • {{ $r['bank']->account_number ?? '-' }} <span class="text-white/60">({{ $r['bank']->account_holder_name ?? '-' }})</span></div>
            @if(isset($r['bank']))
            <button type="button" class="px-3 py-2 rounded bg-white/10 text-white/80 hover:bg-white/20" title="Salin Nomor" data-copy-text="{{ $r['bank']->account_number }}"><i class="fa-regular fa-copy"></i></button>
            @endif
          </div>
          <label class="block text-sm font-medium text-white/90">Rekening Admin</label>
          <select name="admin_bank_account_id" class="w-full p-2 bg-white/10 border border-white/20 rounded" required>
            @foreach($adminAccounts as $acc)
              <option value="{{ $acc->id }}">{{ $acc->bank_name }} • {{ $acc->account_number }}</option>
            @endforeach
          </select>
          <div class="flex gap-2 justify-end">
            <x-ui.btn-secondary type="button" data-toggle-modal data-target="modalPayout{{ $r['user']->id }}">Batal</x-ui.btn-secondary>
            <x-ui.btn-primary type="submit">Cairkan</x-ui.btn-primary>
          </div>
        </form>
      </div>
    </div>
    @endif
  @endforeach

  <script>
    (function(){
      if(window.__admin_commission_modal_loaded) return; 
      window.__admin_commission_modal_loaded = true;
      window.toggleModal = function(id){
        var el = document.getElementById(id);
        if(!el) return;
        var willShow = el.classList.contains('hidden');
        el.classList.toggle('hidden');
        if(willShow){ el.classList.add('grid','place-items-center'); document.body.classList.add('overflow-hidden'); }
        else { el.classList.remove('grid','place-items-center'); document.body.classList.remove('overflow-hidden'); }
      };
      document.querySelectorAll('[data-toggle-modal]').forEach(function(btn){
        btn.addEventListener('click', function(){ var t = btn.getAttribute('data-target'); if(t) window.toggleModal(t); });
      });
      function copyToClipboard(text){ try { navigator.clipboard.writeText(text); } catch(e){ var ta = document.createElement('textarea'); ta.value = text; document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta);} }
      document.querySelectorAll('[data-copy-input]').forEach(function(btn){
        btn.addEventListener('click', function(){ var id = btn.getAttribute('data-copy-input'); var el = document.getElementById(id); if(el){ copyToClipboard(el.value || ''); }});
      });
      document.querySelectorAll('[data-copy-text]').forEach(function(btn){
        btn.addEventListener('click', function(){ var txt = btn.getAttribute('data-copy-text'); if(txt){ copyToClipboard(txt); }});
      });
    })();
  </script>
</div>
@endsection
