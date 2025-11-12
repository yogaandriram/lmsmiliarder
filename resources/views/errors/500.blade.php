@extends('components.layout.app')

@section('content')
  <div class="min-h-[60vh] flex items-center justify-center">
    <div class="glass w-full max-w-lg rounded-2xl p-8 text-center space-y-4">
      <div class="flex justify-center">
        <i class="fa-solid fa-bolt text-pink-300 text-5xl"></i>
      </div>
      <h1 class="text-3xl font-bold">Terjadi Kesalahan di Server</h1>
      <p class="text-sm text-neutral-400">Kode: <span class="font-mono">500</span></p>
      <p class="text-neutral-200">Kami sedang memperbaikinya. Coba kembali beberapa saat lagi.</p>
      <div class="flex gap-3 justify-center pt-3">
            <x-ui.btn-primary href="{{ url('/') }}" icon="fa-solid fa-house">Kembali ke Beranda</x-ui.btn-primary>
            <x-ui.btn-secondary as="button" onclick="history.back()" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
      </div>
    </div>
  </div>
@endsection