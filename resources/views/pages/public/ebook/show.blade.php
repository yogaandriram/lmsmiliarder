@extends('components.layout.app')
@section('page_title', 'E-book: ' . $ebook->title)

@section('content')
<div class="space-y-8">
  <div class="glass p-6 rounded-lg">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
      <div class="rounded-lg overflow-hidden bg-white/5 h-full relative group">
        <img src="{{ $ebook->cover_image_url ?? 'https://placehold.co/400x600' }}" alt="{{ $ebook->title }}" class="w-full h-full object-cover shadow-2xl transition-transform duration-500 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      </div>
      <div class="space-y-6 h-full flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-500 text-xs font-bold uppercase rounded-full border border-yellow-500/30">E-Book</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-4">{{ $ebook->title }}</h1>
            
            @php
            $publishedAt = $ebook->status === 'published' ? ($ebook->verified_at ?? $ebook->created_at) : null;
            $mentor = $ebook->author;
            @endphp
            <div class="flex items-center gap-4 text-sm text-white/70 mb-6">
                 <div class="flex items-center gap-2">
                    <i class="fa-regular fa-calendar"></i>
                    <span>{{ $publishedAt ? $publishedAt->format('d M Y') : '-' }}</span>
                 </div>
                 <div class="w-1 h-1 bg-white/30 rounded-full"></div>
                 <div class="flex items-center gap-2">
                    <i class="fa-regular fa-user"></i>
                    <span>{{ $mentor->name }}</span>
                 </div>
            </div>

            <div class="glass p-5 rounded-xl border border-white/10">
                <h2 class="text-lg font-semibold text-yellow-500 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-align-left"></i> Deskripsi E-book
                </h2>
                <div class="prose prose-invert max-w-none text-white/80 leading-relaxed text-sm">
                    {!! nl2br(e($ebook->description)) !!}
                </div>
            </div>
        </div>
        
        <div class="space-y-4">
             <div class="glass p-4 rounded-xl border border-white/10 flex items-center justify-between">
                <div>
                     <p class="text-sm text-white/50 mb-1">Harga</p>
                    <div class="text-3xl font-bold text-white">Rp {{ number_format($ebook->price, 0, ',', '.') }}</div>
                </div>
                <div>
                     <x-ui.btn-primary href="{{ route('checkout.ebook', $ebook) }}" icon="fa-solid fa-cart-arrow-down" class="!px-8 !py-3 !text-lg shadow-lg shadow-yellow-500/20">
                        Beli Sekarang
                    </x-ui.btn-primary>
                </div>
            </div>
            
            <div class="glass p-4 rounded-xl border border-white/10 flex items-center gap-4">
                 <img src="{{ optional($mentor)->avatar_url ?? 'https://placehold.co/64x64' }}" alt="Avatar Mentor" class="w-12 h-12 rounded-full object-cover border border-white/10">
                 <div>
                    <div class="text-xs text-white/50 uppercase tracking-wider font-bold mb-0.5">Penulis</div>
                   <div class="font-semibold text-white">{{ optional($mentor)->name ?? '-' }}</div>
                 </div>
            </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
