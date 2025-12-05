@extends('components.layout.app')
@section('page_title','Konfirmasi Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Konfirmasi Pembayaran</h2>
    <x-ui.btn-secondary href="{{ route('checkout.transactions.show', $transaction) }}" icon="fa-solid fa-list">Status Pembayaran</x-ui.btn-secondary>
  </div>

  @php
    $bankName = optional($transaction->adminBankAccount)->bank_name;
    $accountNumber = optional($transaction->adminBankAccount)->account_number;
    $accountHolder = optional($transaction->adminBankAccount)->account_holder_name;
    $subtotal = (int)($transaction->total_amount ?? 0);
    $discount = (int)($transaction->discount_amount ?? 0);
    $final = (int)($transaction->final_amount ?? max(0, $subtotal - $discount));
    $unique = (int)($transaction->unique_code ?? 0);
    $payable = (int)($transaction->payable_amount ?? max(0, $final - $unique));
  @endphp

  <div class="glass p-6 rounded-lg">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-white/80">ID Transaksi</div>
        <div class="font-semibold">#{{ $transaction->id }}</div>
      </div>
      <div>
        <span class="px-3 py-1 rounded-full text-xs 
          @if($transaction->payment_status==='success') bg-green-500/20 text-green-300 
          @elseif($transaction->payment_status==='failed') bg-red-500/20 text-red-300 
          @else bg-yellow-500/20 text-yellow-300 @endif">
          {{ ucfirst($transaction->payment_status) }}
        </span>
      </div>
    </div>

    <div class="mt-4">
      <x-ui.table>
        <x-slot name="header">
          <tr>
            <th class="text-left py-3 px-4">Produk</th>
            <th class="text-left py-3 px-4">Tipe</th>
            <th class="text-right py-3 px-4">Harga</th>
          </tr>
        </x-slot>
        @foreach($transaction->details as $d)
          <tr class="border-b border-white/10">
            <td class="py-3 px-4">{{ $d->product_type==='course' ? ($d->course->title ?? 'Kursus') : ($d->ebook->title ?? 'E-book') }}</td>
            <td class="py-3 px-4">{{ ucfirst($d->product_type) }}</td>
            <td class="py-3 px-4 text-right">Rp {{ number_format($d->price,0,',','.') }}</td>
          </tr>
        @endforeach
        <tr>
          <td colspan="2" class="py-2 px-4 text-white/80">Subtotal</td>
          <td class="py-2 px-4 text-right font-semibold">Rp {{ number_format($subtotal,0,',','.') }}</td>
        </tr>
        <tr>
          <td colspan="2" class="py-2 px-4 text-white/80">Diskon</td>
          <td class="py-2 px-4 text-right">- Rp {{ number_format($discount,0,',','.') }}</td>
        </tr>
        <tr>
          <td colspan="2" class="py-2 px-4 text-white/80">Kode Unik</td>
          <td class="py-2 px-4 text-right">- Rp {{ number_format($unique,0,',','.') }}</td>
        </tr>
        <tr>
          <td colspan="2" class="py-2 px-4 text-white/90">Total Bayar</td>
          <td class="py-2 px-4 text-right text-xl font-bold">Rp {{ number_format($payable,0,',','.') }}</td>
        </tr>
      </x-ui.table>
      <div class="flex items-center justify-between mt-3">
        <span class="text-white/80">Total Bayar</span>
        <span class="text-xl font-bold">Rp {{ number_format($payable,0,',','.') }}</span>
      </div>
      @if($transaction->payment_status==='pending' && $transaction->expires_at)
      <div class="mt-3">
        <div class="text-white/70 mb-1">Countdown:</div>
        <div id="countdown_container" data-expires="{{ $transaction->expires_at->toIso8601String() }}" class="grid grid-cols-3 gap-3">
          <div class="glass p-3 rounded text-center">
            <div id="cd_h" class="text-3xl md:text-4xl font-bold">00</div>
            <div class="text-xs">Jam</div>
          </div>
          <div class="glass p-3 rounded text-center">
            <div id="cd_m" class="text-3xl md:text-4xl font-bold">00</div>
            <div class="text-xs">Menit</div>
          </div>
          <div class="glass p-3 rounded text-center">
            <div id="cd_s" class="text-3xl md:text-4xl font-bold">00</div>
            <div class="text-xs">Detik</div>
          </div>
        </div>
      </div>
      @endif
      @if($transaction->adminBankAccount)
      <div class="mt-4 p-4 rounded bg-white/5">
        <div class="text-sm text-white/70 mb-2">Rekening yang dipilih</div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
          <div>
            <div class="font-medium">{{ $transaction->adminBankAccount->bank_name }}</div>
            <div class="text-sm">Atas Nama: {{ $transaction->adminBankAccount->account_holder_name }}</div>
          </div>
          <div class="font-mono flex items-center gap-3">
            <span id="acc_number_text">{{ $transaction->adminBankAccount->account_number }}</span>
            <button type="button" class="px-2 py-1 bg-white/10 rounded" data-copy="{{ $transaction->adminBankAccount->account_number }}">Salin</button>
          </div>
          <div class="flex items-center gap-3">
            <span>Nominal: <strong id="payable_text_inline">Rp {{ number_format($payable,0,',','.') }}</strong></span>
            <button type="button" class="px-2 py-1 bg-white/10 rounded" data-copy="{{ $payable }}">Salin</button>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  @if($transaction->payment_status!=='success')
  <div class="glass p-6 rounded-lg">
    <h3 class="text-lg font-semibold text-yellow-400 mb-4">Unggah Bukti Pembayaran</h3>
    
    <form method="POST" action="{{ route('checkout.payments.upload', $transaction) }}" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <x-ui.crud.input label="Bukti Pembayaran" name="payment_proof" type="file" accept="image/*,.pdf" required variant="glass" />
        </div>
        <div>
          <x-ui.crud.input label="Nama Pengirim" name="sender_name" type="text" variant="glass" />
        </div>
        <div>
          <x-ui.crud.input label="No. Rekening Pengirim" name="sender_account_no" type="text" variant="glass" />
        </div>
        <div>
          <x-ui.crud.input label="Bank Asal Transfer" name="origin_bank" type="text" variant="glass" />
        </div>
        <div>
          <x-ui.crud.input label="Bank Tujuan Transfer" name="destination_bank" type="text" variant="glass" value="{{ $bankName }}" readonly />
        </div>
        <div>
          <x-ui.crud.input label="Nominal Transfer" name="transfer_amount" type="number" min="0" variant="glass" value="{{ $payable }}" readonly />
        </div>
        <div class="md:col-span-2">
          <x-ui.crud.textarea label="Deskripsi / Catatan" name="transfer_note" rows="3" variant="glass" />
        </div>
      </div>
      
      <x-ui.btn-primary type="submit" icon="fa-solid fa-upload">Kirim Bukti</x-ui.btn-primary>
      @if($transaction->payment_proof_url)
        <p class="text-white/70 text-sm">Bukti saat ini: <a class="text-yellow-300 underline" href="{{ $transaction->payment_proof_url }}" target="_blank">Lihat</a></p>
      @endif
      <p class="text-xs text-white/60">Setelah bukti dikirim, status akan menjadi pending dan diverifikasi admin.</p>
    </form>
  </div>
  @endif
</div>
<script>
(function(){
  var cont = document.getElementById('countdown_container');
  if(!cont) return;
  var expIso = cont.getAttribute('data-expires');
  var expDate = new Date(expIso);
  var hEl = document.getElementById('cd_h');
  var mEl = document.getElementById('cd_m');
  var sEl = document.getElementById('cd_s');
  function tick(){
    var now = new Date();
    var diff = Math.max(0, expDate.getTime() - now.getTime());
    var s = Math.floor(diff/1000);
    var h = String(Math.floor(s/3600)).padStart(2,'0');
    var m = String(Math.floor((s%3600)/60)).padStart(2,'0');
    var sec = String(s%60).padStart(2,'0');
    if(hEl) hEl.textContent = h;
    if(mEl) mEl.textContent = m;
    if(sEl) sEl.textContent = sec;
  }
  tick();
  setInterval(tick, 1000);

  function copyText(val, btn){
    try {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(String(val)).then(function(){
          btn.textContent = 'Disalin'; setTimeout(function(){ btn.textContent = 'Salin'; }, 1500);
        });
      } else {
        var ta = document.createElement('textarea'); ta.value = String(val); document.body.appendChild(ta); ta.select();
        document.execCommand('copy'); document.body.removeChild(ta);
        btn.textContent = 'Disalin'; setTimeout(function(){ btn.textContent = 'Salin'; }, 1500);
      }
    } catch(e){}
  }

  document.querySelectorAll('[data-copy]').forEach(function(btn){
    btn.addEventListener('click', function(){
      var val = btn.getAttribute('data-copy');
      copyText(val, btn);
    });
  });
})();
</script>
@endsection
