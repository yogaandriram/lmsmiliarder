@extends('components.layout.app')
@section('page_title','Checkout Kursus')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Checkout Kursus</h2>
    <x-ui.btn-secondary href="{{ route('public.courses.show', $course->slug) }}" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
  </div>

  <div class="grid grid-cols-1 place-items-center gap-6">
    <div class="w-full max-w-3xl glass p-6 rounded-lg">
      <div class="flex items-center gap-4 mb-4">
        <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/120x80' }}" class="w-24 h-16 rounded object-cover" alt="{{ $course->title }}">
        <div>
          <div class="text-lg font-semibold">{{ $course->title }}</div>
          <div class="text-white/70 text-sm">Oleh {{ $course->author->name }}</div>
        </div>
      </div>
      <div class="border-t border-white/10 mt-4 pt-4">
        @php
          $couponCode = trim((string)request('coupon'));
          $discountPreview = 0.0; $couponMsg = null;
          if ($couponCode !== '') {
            $cp = \App\Models\Coupon::where('code',$couponCode)
              ->where(function($q){ $q->where('is_active',true)->orWhereNull('is_active'); })
              ->first();
            if (!$cp) {
              $couponMsg = 'Kode kupon tidak ditemukan.';
            } else if ($cp->expires_at && now()->gt($cp->expires_at)) {
              $couponMsg = 'Kupon sudah kedaluwarsa.';
            } else {
              $used = \App\Models\Transaction::where('coupon_id',$cp->id)->count();
              if (!is_null($cp->usage_limit) && $used >= (int)$cp->usage_limit) {
                $couponMsg = 'Kupon telah mencapai batas penggunaan.';
              } else {
                if ($cp->discount_type === 'percentage') { $discountPreview = min((float)$course->price, ((float)$course->price * (float)$cp->discount_value) / 100.0); }
                else { $discountPreview = min((float)$course->price, (float)$cp->discount_value); }
                $couponMsg = 'Kupon diterapkan.';
              }
            }
          }
          $finalPreview = max(0.0, (float)$course->price - (float)$discountPreview);
        @endphp
        <div class="flex items-center justify-between">
          <span class="text-white/80">Harga</span>
          <span class="font-semibold">Rp {{ number_format($course->price,0,',','.') }}</span>
        </div>
        <div class="flex items-center justify-between mt-2">
          <span class="text-white/80">Diskon</span>
          <span class="font-semibold">Rp {{ number_format($discountPreview,0,',','.') }}</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <span class="text-white/90">Total</span>
          <span class="text-xl font-bold">Rp {{ number_format($finalPreview,0,',','.') }}</span>
        </div>
        <form method="GET" action="{{ url()->current() }}" class="mt-3 mb-3 flex items-center justify-between gap-3">
          <div class="flex-1">
            <x-ui.crud.input name="coupon" type="text" placeholder="Masukkan kode kupon" :value="request('coupon')" variant="glass" class="focus:ring-0 focus:outline-none focus:border-yellow-400" />
          </div>
          <x-ui.btn-secondary type="submit" icon="fa-solid fa-ticket" class="ml-3">Terapkan</x-ui.btn-secondary>
        </form>
        @if($couponCode !== '')
          <p class="text-xs {{ $discountPreview>0 ? 'text-green-300' : 'text-red-300' }}">{{ $couponMsg }}</p>
        @endif
        <div class="mt-4">
          <h3 class="text-sm font-semibold text-yellow-400 mb-2">Pilih Rekening Bank Admin</h3>
          @php 
            $accounts = \App\Models\AdminBankAccount::where('is_active', true)->orderBy('bank_name')->get(); 
            if(!$accounts->count()) { $accounts = \App\Models\AdminBankAccount::orderBy('bank_name')->get(); }
            $initialBank = (int)request('bank');
          @endphp
          @if($accounts->count())
            <div id="bank_cards_course" class="grid grid-cols-2 md:grid-cols-4 gap-3">
              @foreach($accounts as $acc)
                @php $active = $initialBank === (int)$acc->id; @endphp
                <div class="bank-card cursor-pointer p-4 rounded bg-white/5 border {{ $active ? 'ring-2 ring-yellow-400 border-yellow-400/40' : 'border-white/20 hover:border-yellow-400/40' }}" data-id="{{ $acc->id }}">
                  <div class="font-medium">{{ $acc->bank_name }}</div>
                </div>
              @endforeach
            </div>
            <input type="hidden" id="selected_bank_course" value="{{ $initialBank > 0 ? $initialBank : '' }}">
            <p class="text-xs text-white/60 mt-2">Pilih salah satu rekening. Pilihan Anda akan tersimpan pada transaksi.</p>
          @else
            <p class="text-white/70 text-sm">Belum ada rekening admin.</p>
          @endif
        </div>
      </div>
      @if(Auth::check())
      <form method="POST" action="{{ route('member.checkout.course.purchase', $course) }}" class="mt-6" id="checkout_form_course">
        @csrf
        @if($couponCode)
          <input type="hidden" name="coupon_code" value="{{ $couponCode }}">
        @endif
        <input type="hidden" name="bank_id" id="bank_id_course" value="{{ $initialBank > 0 ? $initialBank : '' }}">
        <x-ui.btn-primary type="submit" class="w-full justify-center" icon="fa-solid fa-credit-card">Bayar Sekarang</x-ui.btn-primary>
      </form>
      @else
        <div class="mt-6">
          <x-ui.btn-primary type="button" id="trigger_login_prompt_course" class="w-full justify-center" icon="fa-solid fa-credit-card">Bayar Sekarang</x-ui.btn-primary>
        </div>
      @endif
    </div>

    
  </div>
</div>
<div id="login_prompt_modal_course" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
  <div class="glass p-6 rounded w-full max-w-md relative">
    <button type="button" id="close_login_prompt_course" class="absolute top-2 right-2 text-white/80 hover:text-yellow-400">
      <i class="fa-solid fa-xmark text-xl"></i>
    </button>
    <h4 class="text-lg font-semibold mb-2">Masuk terlebih dahulu</h4>
    @php $retUrl = url()->current().(request('coupon') ? ('?coupon='.urlencode(request('coupon'))) : ''); @endphp
    <p class="text-sm text-white/70 mb-4">Untuk melanjutkan checkout, silakan masuk atau daftar jika belum punya akun.</p>
    <div class="grid grid-cols-2 gap-4">
      <div class="flex justify-start">
        <x-ui.btn-secondary class="min-w-[180px]" href="{{ route('login',['return'=>$retUrl]) }}" icon="fa-solid fa-right-to-bracket">Masuk</x-ui.btn-secondary>
      </div>
      <div class="flex justify-end">
        <x-ui.btn-primary class="min-w-[180px]" href="{{ route('register',['return'=>$retUrl]) }}" icon="fa-solid fa-user-plus">Daftar</x-ui.btn-primary>
      </div>
    </div>
  </div>
</div>
<script>
(function(){
  var btn = document.getElementById('trigger_login_prompt_course');
  var modal = document.getElementById('login_prompt_modal_course');
  var close = document.getElementById('close_login_prompt_course');
  var bankGrid = document.getElementById('bank_cards_course');
  var selHidden = document.getElementById('selected_bank_course');
  var bankIdField = document.getElementById('bank_id_course');
  var applyForm = document.querySelector('form[method="GET"]');
  var checkoutForm = document.getElementById('checkout_form_course');
  if(btn && modal){ btn.addEventListener('click', function(){ modal.classList.remove('hidden'); modal.classList.add('grid','place-items-center'); }); }
  if(close && modal){ close.addEventListener('click', function(){ modal.classList.add('hidden'); modal.classList.remove('grid','place-items-center'); }); }
  if(bankGrid){
    bankGrid.addEventListener('click', function(e){
      var card = e.target.closest('.bank-card');
      if(!card) return;
      var id = card.dataset.id;
      selHidden.value = id;
      if(bankIdField) bankIdField.value = id;
      bankGrid.querySelectorAll('.bank-card').forEach(function(c){ c.classList.remove('ring-2','ring-yellow-400','border-yellow-400/40'); c.classList.add('border-white/20'); });
      card.classList.add('ring-2','ring-yellow-400'); card.classList.remove('border-white/20'); card.classList.add('border-yellow-400/40');
    });
  }
  if(applyForm){
    var hidden = document.createElement('input'); hidden.type='hidden'; hidden.name='bank'; hidden.value = selHidden ? selHidden.value : ''; applyForm.appendChild(hidden);
    applyForm.addEventListener('submit', function(){ hidden.value = selHidden ? selHidden.value : ''; });
  }
  if(checkoutForm){
    checkoutForm.addEventListener('submit', function(ev){
      var id = bankIdField ? bankIdField.value : '';
      if(!id){ ev.preventDefault(); alert('Pilih rekening tujuan pembayaran terlebih dahulu'); }
    });
  }
})();
</script>
@endsection
