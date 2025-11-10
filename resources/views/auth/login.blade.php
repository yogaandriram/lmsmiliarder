@extends('components.layout.auth')

@section('content')
<form method="POST" action="{{ url('/login') }}" class="space-y-4 text-gray-900 dark:text-gray-100">
    @csrf
    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Masuk ke Akun Anda</h2>

<x-form.input label="Email" name="email" type="email" value="{{ old('email') }}" placeholder="email@gmail.com" icon="fa-solid fa-envelope" required />
    @error('email')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

<x-form.input label="Kata Sandi" name="password" type="password" placeholder="********" icon="fa-solid fa-lock" required showToggle="true" />
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

    <button type="button" class="w-full border border-gray-300 text-gray-700 rounded-lg px-4 py-2 hover:border-yellow-400 hover:text-yellow-500 dark:border-white/20 dark:text-gray-200 dark:hover:text-yellow-400">
        <i class="fa-brands fa-google mr-2"></i> Akun Google
    </button>

    <div class="align-center text-center">
        <p class="text-gray-600 dark:text-gray-400 text-sm">Belum punya akun? <a href="{{ route('register') }}" class="text-yellow-400 hover:text-yellow-300">Registrasi Sekarang</a></p>
    </div>

    
</form>

 
@endsection