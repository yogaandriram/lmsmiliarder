@extends('layouts.auth')

@section('content')
<h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">OTP Verifikasi</h2>
<p class="text-gray-600 dark:text-gray-400 text-sm">Silakan masukkan kode 6 digit yang dikirim ke email Anda</p>

@if(session('status'))
    <p class="text-green-600 dark:text-green-400 text-sm">{{ session('status') }}</p>
@endif

<form id="otpVerifyForm" method="POST" action="{{ route('otp.verify') }}" class="mt-3 space-y-3 text-gray-900 dark:text-gray-100">
    @csrf
    <input type="hidden" name="email" value="{{ $email ?? old('email') }}" />
    <input type="hidden" name="code" id="otpCode" />

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

    <button type="submit" class="w-full bg-yellow-400 text-black font-semibold rounded-lg px-4 py-2 hover:bg-yellow-300">Kirim Link</button>
</form>

<div class="flex items-center justify-between mt-3">
    <span class="text-gray-600 dark:text-gray-400 text-sm">Tidak Menerima OTP?</span>
    <form id="resendForm" method="POST" action="{{ route('otp.request') }}" class="flex items-center gap-3">
        @csrf
        <input type="hidden" name="email" value="{{ $email ?? old('email') }}" />
        <button type="submit" class="border border-gray-300 text-gray-700 rounded-lg px-3 py-2 hover:border-yellow-400 hover:text-yellow-500 disabled:opacity-50 dark:border-white/20 dark:text-gray-200 dark:hover:text-yellow-400" id="resendBtn" data-seconds="{{ isset($remainingSeconds) ? max(0, (int)$remainingSeconds) : 60 }}" disabled>Kirim Ulang OTP</button>
        <span class="text-gray-600 dark:text-gray-400 text-sm" id="countdownText">Kirim Ulang Dalam 0:{{ str_pad(($remainingSeconds ?? 60) % 60, 2, '0', STR_PAD_LEFT) }}</span>
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