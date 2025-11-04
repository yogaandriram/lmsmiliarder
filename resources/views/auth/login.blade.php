@extends('layouts.auth')

@section('content')
<form method="POST" action="{{ url('/login') }}" class="space-y-4 text-gray-900 dark:text-gray-100">
    @csrf
    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Masuk ke Akun Anda</h2>

    <label class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">@</span>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@gmail.com" class="w-full py-2 pl-8 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400" />
    </div>
    @error('email')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

    <label class="block text-sm text-gray-700 dark:text-gray-300">Kata Sandi</label>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">*</span>
        <input type="password" name="password" required placeholder="********" class="w-full py-2 pl-8 pr-10 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400" />
        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 dark:text-gray-400 dark:hover:text-yellow-400" onclick="togglePassword(this)">👁️</button>
    </div>
    @error('password')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

    <div class="flex items-center justify-between text-sm">
        <label class="inline-flex items-center gap-2 text-gray-700 dark:text-gray-300">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="rounded border-gray-300 bg-white dark:border-white/20 dark:bg-neutral-900" /> Remember Me
        </label>
        <a href="{{ route('password.forgot') }}" class="text-gray-600 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-400">Lupa Kata Sandi?</a>
    </div>

    <button type="submit" class="w-full bg-yellow-400 text-black font-semibold rounded-lg px-4 py-2 hover:bg-yellow-300">Masuk</button>

    <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
        <div class="h-px bg-gray-300 dark:bg-white/10 w-full"></div>
        <span>Or</span>
        <div class="h-px bg-gray-300 dark:bg-white/10 w-full"></div>
    </div>

    <button type="button" class="w-full border border-gray-300 text-gray-700 rounded-lg px-4 py-2 hover:border-yellow-400 hover:text-yellow-500 dark:border-white/20 dark:text-gray-200 dark:hover:text-yellow-400">Akun Google</button>

    <div class="align-center text-center">
        <p class="text-gray-600 dark:text-gray-400 text-sm">Belum punya akun? <a href="{{ route('register') }}" class="text-yellow-400 hover:text-yellow-300">Registrasi Sekarang</a></p>
    </div>

    
</form>

<script>
function togglePassword(btn){
  const input = btn.previousElementSibling;
  if(!input) return;
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection