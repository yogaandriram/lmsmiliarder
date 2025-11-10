@extends('components.layout.app')

@section('content')
<div class="space-y-6">
  <div class="rounded-xl border bg-white text-gray-900 p-6 shadow-sm
              dark:bg-neutral-900 dark:text-gray-100 dark:border-white/10">
    <h1 class="text-2xl font-semibold">Selamat datang di EduLux LMS</h1>
    <p class="text-gray-600 dark:text-gray-400">Contoh halaman beranda yang menggunakan layout umum.</p>

    <div class="mt-4 flex items-center gap-3">
      <button class="p-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300 theme-toggle
                                  dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
        <i data-theme-icon class="fa-regular fa-sun"></i>
        <span class="ml-2">Toggle Mode</span>
      </button>
      <span class="text-sm text-gray-600 dark:text-gray-400">Gunakan tombol di navbar atau di sini.</span>
    </div>
  </div>

  <div class="grid sm:grid-cols-2 gap-6">
    <div class="rounded-xl border bg-white p-4 dark:bg-neutral-900 dark:border-white/10">
      <h2 class="font-semibold">Fitur</h2>
      <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300">
        <li>Autentikasi dengan halaman khusus</li>
        <li>Dark mode dengan penyimpanan preferensi</li>
        <li>Font Awesome icon konsisten</li>
      </ul>
    </div>
    <div class="rounded-xl border bg-white p-4 dark:bg-neutral-900 dark:border-white/10">
      <h2 class="font-semibold">Aksi Cepat</h2>
      <div class="mt-2 flex gap-3">
        <a href="{{ route('login') }}" class="px-4 py-2 rounded bg-yellow-400 text-black hover:bg-yellow-300">Masuk</a>
        <a href="{{ route('register') }}" class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:border-yellow-400 hover:text-yellow-500 dark:border-white/20 dark:text-gray-200 dark:hover:text-yellow-400">Daftar</a>
      </div>
    </div>
  </div>
</div>
@endsection