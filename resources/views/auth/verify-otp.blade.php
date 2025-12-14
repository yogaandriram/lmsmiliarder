@extends('components.layout.auth')

@section('content')
<h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 text-center">OTP Verifikasi</h2>
<p class="text-gray-600 dark:text-gray-400 text-sm text-center">Silakan masukkan kode 6 digit yang dikirim ke email Anda</p>

@if(session('status'))
    <p class="text-green-600 dark:text-green-400 text-sm">{{ session('status') }}</p>
@endif

<form id="otpVerifyForm" method="POST" action="{{ route('otp.verify') }}" class="mt-3 space-y-3 text-gray-900 dark:text-gray-100">
    @csrf
    <input type="hidden" name="email" value="{{ $email ?? old('email') }}" />
    <input type="hidden" name="code" id="otpCode" />
    @if(isset($return))
      <input type="hidden" name="return" value="{{ $return }}" />
    @endif

    <div class="flex justify-center gap-3 my-3">
        <input class="otp-box w-12 h-12 rounded-lg border bg-white text-gray-900 text-center text-xl outline-none border-gray-300 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:border-white/15 dark:focus:ring-yellow-400 dark:focus:border-yellow-400" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" />
        <input class="otp-box w-12 h-12 rounded-lg border bg-white text-gray-900 text-center text-xl outline-none border-gray-300 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:border-white/15 dark:focus:ring-yellow-400 dark:focus:border-yellow-400" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" />
        <input class="otp-box w-12 h-12 rounded-lg border bg-white text-gray-900 text-center text-xl outline-none border-gray-300 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:border-white/15 dark:focus:ring-yellow-400 dark:focus:border-yellow-400" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" />
        <input class="otp-box w-12 h-12 rounded-lg border bg-white text-gray-900 text-center text-xl outline-none border-gray-300 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:border-white/15 dark:focus:ring-yellow-400 dark:focus:border-yellow-400" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" />
        <input class="otp-box w-12 h-12 rounded-lg border bg-white text-gray-900 text-center text-xl outline-none border-gray-300 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:border-white/15 dark:focus:ring-yellow-400 dark:focus:border-yellow-400" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" />
        <input class="otp-box w-12 h-12 rounded-lg border bg-white text-gray-900 text-center text-xl outline-none border-gray-300 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:border-white/15 dark:focus:ring-yellow-400 dark:focus:border-yellow-400" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" />
    </div>
    @error('code')
        <p class="text-red-400 text-sm">{{ $message }}</p>
    @enderror

    <x-ui.btn-primary type="submit" class="w-full justify-center mb-4">Kirim Link</x-ui.btn-primary>
</form>

<div class="space-y-3 mt-4">
    <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
        <span>Tidak Menerima OTP?</span>
        <span id="countdownText" class="font-medium">Kirim Ulang Dalam 0:{{ str_pad(($remainingSeconds ?? 60) % 60, 2, '0', STR_PAD_LEFT) }}</span>
    </div>

    <form id="resendForm" method="POST" action="{{ route('otp.request') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email ?? old('email') }}" />
        @if(isset($return))
          <input type="hidden" name="return" value="{{ $return }}" />
        @endif
        <x-ui.btn-secondary type="submit" size="sm" class="w-full justify-center disabled:opacity-50" id="resendBtn" data-seconds="{{ isset($remainingSeconds) ? max(0, (int)$remainingSeconds) : 60 }}" disabled>Kirim Ulang OTP</x-ui.btn-secondary>
    </form>
</div>

<p class="text-gray-600 dark:text-gray-500 text-xs mt-2">Maksimal 3 kali permintaan dalam 1 jam.</p>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const inputs = Array.from(document.querySelectorAll('.otp-box'));
  const hidden = document.getElementById('otpCode');
  const form = document.getElementById('otpVerifyForm');

  const sanitize = (v) => v.replace(/\D/g, '').slice(0,1);
  inputs.forEach((inp, i) => {
    inp.addEventListener('input', (e) => {
      inp.value = sanitize(inp.value);
      if (inp.value && i < inputs.length - 1) inputs[i+1].focus();
      hidden.value = inputs.map(x => x.value).join('');
    });
    inp.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !inp.value && i > 0) inputs[i-1].focus();
    });
  });

  form.addEventListener('submit', (e) => {
    const code = inputs.map(x => x.value).join('');
    if (code.length !== 6) {
      e.preventDefault();
      alert('Masukkan 6 digit kode OTP.');
    }
  });

  const btn = document.getElementById('resendBtn');
  const text = document.getElementById('countdownText');
  let seconds = Number(btn.dataset.seconds || 60);
  const tick = () => {
    if (seconds <= 0) {
      btn.disabled = false;
      text.textContent = 'Kirim Ulang OTP';
      clearInterval(timer);
      return;
    }
    const m = Math.floor(seconds/60);
    const s = ('0' + (seconds%60)).slice(-2);
    text.textContent = `Kirim Ulang Dalam ${m}:${s}`;
    seconds--;
  };
  tick();
  const timer = setInterval(tick, 1000);
});
</script>
@endsection
