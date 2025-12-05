@extends('components.layout.auth')

@section('content')
<form method="POST" action="{{ route('password.email') }}" class="space-y-4 text-gray-900 dark:text-gray-100">
    @csrf
    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Lupa Kata Sandi</h2>
    <p class="text-gray-600 dark:text-gray-400 text-sm">Masukkan email Anda untuk menerima link atur ulang.</p>

    @if(session('status'))
        <p class="text-green-600 dark:text-green-400 text-sm">{{ session('status') }}</p>
    @endif

    <x-ui.auth.input label="Email" name="email" type="email" value="{{ old('email') }}" placeholder="email@gmail.com" icon="fa-solid fa-envelope" required />

    <x-ui.btn-primary type="submit" class="w-full justify-center">Kirim Link</x-ui.btn-primary>
    <p class="text-gray-600 dark:text-gray-400 text-sm">Ingat kata sandi? <a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300">Masuk</a></p>
</form>
@endsection
