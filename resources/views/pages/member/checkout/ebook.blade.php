@extends('components.layout.member')
@section('page_title','Checkout E-book')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Checkout E-book</h2>
    <x-ui.btn-secondary href="{{ route('home') }}" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
  </div>

  <div class="glass p-6 rounded-lg">
    <div class="flex items-center gap-4 mb-4">
      <img src="{{ $ebook->cover_image_url ?? 'https://placehold.co/120x80' }}" class="w-24 h-16 rounded object-cover" alt="{{ $ebook->title }}">
      <div>
        <div class="text-lg font-semibold">{{ $ebook->title }}</div>
        <div class="text-white/70 text-sm">Oleh {{ $ebook->author->name }}</div>
      </div>
    </div>
    <div class="border-t border-white/10 mt-4 pt-4">
      <div class="flex items-center justify-between">
        <span class="text-white/80">Harga</span>
        <span class="font-semibold">Rp {{ number_format($ebook->price,0,',','.') }}</span>
      </div>
      <div class="flex items-center justify-between mt-2">
        <span class="text-white/80">Diskon</span>
        <span class="font-semibold">Rp 0</span>
      </div>
      <div class="flex items-center justify-between mt-3">
        <span class="text-white/90">Total</span>
        <span class="text-xl font-bold">Rp {{ number_format($ebook->price,0,',','.') }}</span>
      </div>
    </div>
    <form method="POST" action="{{ route('member.checkout.ebook.purchase', $ebook) }}" class="mt-6">
      @csrf
      <x-ui.btn-primary type="submit" class="w-full justify-center" icon="fa-solid fa-credit-card">Bayar Sekarang</x-ui.btn-primary>
    </form>
    <p class="text-xs text-white/60 mt-2">Dengan melanjutkan, Anda setuju dengan syarat dan ketentuan pembelian.</p>
  </div>
</div>
@endsection

