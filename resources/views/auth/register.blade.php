@extends('layouts.auth')

@section('content')
<form method="POST" action="{{ url('/register') }}" class="space-y-4 text-gray-900 dark:text-gray-100">
    @csrf
    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Registrasi Akun</h2>

    <label class="block text-sm text-gray-700 dark:text-gray-300">Nama</label>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2">👤</span>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap" class="w-full pl-8 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400" />
    </div>
    @error('name')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

    <label class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">@</span>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@gmail.com" class="w-full pl-8 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400" />
    </div>
    @error('email')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

    <label class="block text-sm text-gray-700 dark:text-gray-300">Kata Sandi</label>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">*</span>
        <input type="password" name="password" required placeholder="********" class="w-full pl-8 pr-10 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400" />
        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 dark:text-gray-400 dark:hover:text-yellow-400" onclick="togglePassword(this)">👁️</button>
    </div>
    @error('password')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

    <label class="block text-sm text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi</label>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">*</span>
        <input type="password" name="password_confirmation" required placeholder="********" class="w-full pl-8 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400" />
    </div>

    <button type="submit" class="w-full bg-yellow-400 text-black font-semibold rounded-lg px-4 py-2 hover:bg-yellow-300">Daftar</button>

    <p class="text-gray-600 dark:text-gray-400 text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300">Masuk</a></p>
</form>

<script>
function togglePassword(btn){
  const input = btn.previousElementSibling;
  if(!input) return;
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection