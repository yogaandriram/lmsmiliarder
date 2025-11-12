@extends('components.layout.auth')

@section('content')
<form method="POST" action="{{ route('password.email') }}" class="space-y-4 text-gray-900 dark:text-gray-100">
    @csrf
    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Lupa Kata Sandi</h2>
    <p class="text-gray-600 dark:text-gray-400 text-sm">Masukkan email Anda untuk menerima link atur ulang.</p>

    @if(session('status'))
        <p class="text-green-600 dark:text-green-400 text-sm">{{ session('status') }}</p>
    @endif

    <label class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">@</span>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@gmail.com" class="w-full pl-8 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400" />
    </div>
    @error('email')
        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
    @enderror

    <x-ui.btn-primary type="submit" class="w-full justify-center">Kirim Link</x-ui.btn-primary>
    <p class="text-gray-600 dark:text-gray-400 text-sm">Ingat kata sandi? <a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300">Masuk</a></p>
</form>
@endsection