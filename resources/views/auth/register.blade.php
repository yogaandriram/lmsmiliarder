@extends('components.layout.auth')

@section('content')
<form method="POST" action="{{ url('/register') }}" class="space-y-4 text-gray-900 dark:text-gray-100">
    @csrf
    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Registrasi Akun</h2>

<x-ui.auth.input label="Nama" name="name" type="text" value="{{ old('name') }}" placeholder="Nama Lengkap" icon="fa-solid fa-user" required />
    @error('name')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

<x-ui.auth.input label="Email" name="email" type="email" value="{{ old('email') }}" placeholder="email@gmail.com" icon="fa-solid fa-envelope" required />
    @error('email')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

<x-ui.auth.input label="Kata Sandi" name="password" type="password" placeholder="********" icon="fa-solid fa-lock" required showToggle="true" />
    @error('password')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

<x-ui.auth.input label="Konfirmasi Kata Sandi" name="password_confirmation" type="password" placeholder="********" icon="fa-solid fa-lock" required />

    <x-ui.btn-primary type="submit" class="w-full justify-center">Daftar</x-ui.btn-primary>

    <p class="text-gray-600 dark:text-gray-400 text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300">Masuk</a></p>
</form>

 
@endsection