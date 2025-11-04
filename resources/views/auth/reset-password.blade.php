@extends('layouts.auth')

@section('content')
<form method="POST" action="{{ route('password.reset') }}" class="space-y-4 text-gray-900 dark:text-gray-100">
    @csrf
    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Atur Ulang Kata Sandi</h2>
    <p class="text-gray-600 dark:text-gray-400 text-sm">Silakan masukkan kata sandi baru Anda.</p>

    <input type="hidden" name="email" value="{{ $email }}" />
    <input type="hidden" name="token" value="{{ $token }}" />

    <label class="block text-sm text-gray-700 dark:text-gray-300">Kata Sandi Baru</label>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">*</span>
        <input type="password" name="password" required placeholder="********" class="w-full pl-8 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400" />
    </div>
    @error('password')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

    <label class="block text-sm text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi</label>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">*</span>
        <input type="password" name="password_confirmation" required placeholder="********" class="w-full pl-8 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400" />
    </div>

    @error('email')
        <p class="text-red-400 text-sm">{{ $message }}</p>
    @enderror

    <button type="submit" class="w-full bg-yellow-400 text-black font-semibold rounded-lg px-4 py-2 hover:bg-yellow-300">Simpan Kata Sandi</button>
    <p class="text-gray-600 dark:text-gray-400 text-sm">Sudah ingat? <a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300">Masuk</a></p>
</form>
@endsection