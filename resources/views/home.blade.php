@extends('components.layout.app')
@section('page_title','Beranda')

@section('content')
<div class="space-y-10">
  <section class="relative overflow-hidden rounded-2xl">
    <div class="absolute -inset-16 rounded-[2rem] pointer-events-none">
      <div class="absolute inset-0 blur-[90px] opacity-100 bg-[radial-gradient(120%_80%_at_70%_60%,rgba(234,179,8,0.28),rgba(234,179,8,0.12),transparent)] dark:bg-[radial-gradient(120%_80%_at_70%_60%,rgba(234,179,8,0.22),rgba(234,179,8,0.1),transparent)]"></div>
      <div class="absolute inset-0 blur-[60px] bg-[radial-gradient(120%_80%_at_30%_30%,rgba(255,255,255,0.12),transparent)] dark:bg-[radial-gradient(120%_80%_at_30%_30%,rgba(255,255,255,0.06),transparent)]"></div>
    </div>
    <div class="relative z-10 glass p-8 rounded-2xl">
      <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
        <div class="flex-1 space-y-4">
          <h1 class="text-3xl md:text-4xl font-bold">Belajar Skill Terbaru di EduLux</h1>
          <p class="text-white/70">Kursus berkualitas dan e-book pilihan dari para mentor profesional.</p>
          <div class="max-w-lg"><x-ui.search name="q" placeholder="Cari kursus atau e-book" /></div>
          <div class="flex gap-3">
            <a href="#courses" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-400 text-black rounded shadow"><i class="fa-solid fa-graduation-cap"></i><span>Jelajahi Kursus</span></a>
            <a href="#ebooks" class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/15 rounded"><i class="fa-solid fa-book"></i><span>E-book</span></a>
          </div>
        </div>
        
      </div>
    </div>
  </section>

  @php
    $courses = \App\Models\Course::where('status','published')
      ->with(['author','category'])
      ->latest()->take(6)->get();
    $ebooks = \App\Models\Ebook::where('status','published')
      ->where('verification_status','approved')
      ->with('author')
      ->latest()->take(6)->get();
  @endphp

  <section id="courses" class="space-y-4">
    <h2 class="text-2xl font-semibold">Kursus Terbaru</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @forelse($courses as $c)
        <div class="glass p-4 rounded-lg">
          <img src="{{ $c->thumbnail_url ?? 'https://placehold.co/480x270' }}" class="w-full h-36 rounded object-cover mb-3" alt="{{ $c->title }}">
          <div class="font-semibold">{{ $c->title }}</div>
          <div class="text-sm text-white/70">{{ $c->author->name ?? '-' }} • {{ $c->category->name ?? '-' }}</div>
          <div class="text-sm text-white/80 mt-2">{{ \Illuminate\Support\Str::limit($c->description, 120) }}</div>
          <div class="mt-3">
            <span class="text-white/80">Rp {{ number_format($c->price,0,',','.') }}</span>
          </div>
          <div class="grid grid-cols-2 gap-2 mt-2">
            <div class="flex justify-start">
              <x-ui.btn-secondary size="sm" href="{{ route('public.courses.show.by_author', [\Illuminate\Support\Str::slug($c->author->name ?? 'mentor'), $c->slug]) }}" icon="fa-solid fa-eye">Detail</x-ui.btn-secondary>
            </div>
            <div class="flex justify-end">
              <x-ui.btn-primary size="sm" href="{{ route('checkout.course', $c) }}" icon="fa-solid fa-cart-shopping">Checkout</x-ui.btn-primary>
            </div>
          </div>
        </div>
      @empty
        <div class="text-white/60">Belum ada kursus.</div>
      @endforelse
    </div>
  </section>

  <section id="ebooks" class="space-y-4">
    <h2 class="text-2xl font-semibold">E-book Terbaru</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @forelse($ebooks as $e)
        <div class="glass p-4 rounded-lg">
          <img src="{{ $e->cover_image_url ?? 'https://placehold.co/480x270' }}" class="w-full h-36 rounded object-cover mb-3" alt="{{ $e->title }}">
          <div class="font-semibold">{{ $e->title }}</div>
          <div class="text-sm text-white/70">{{ $e->author->name ?? '-' }}</div>
          <div class="text-sm text-white/80 mt-2">{{ \Illuminate\Support\Str::limit($e->description, 120) }}</div>
          <div class="mt-3">
            <span class="text-white/80">Rp {{ number_format($e->price,0,',','.') }}</span>
          </div>
          <div class="grid grid-cols-2 gap-2 mt-2">
            <div class="flex justify-start">
              <x-ui.btn-secondary size="sm" href="#" icon="fa-solid fa-eye">Detail</x-ui.btn-secondary>
            </div>
            <div class="flex justify-end">
              <x-ui.btn-primary size="sm" href="{{ route('checkout.ebook', $e) }}" icon="fa-solid fa-cart-shopping">Checkout</x-ui.btn-primary>
            </div>
          </div>
        </div>
      @empty
        <div class="text-white/60">Belum ada e-book.</div>
      @endforelse
    </div>
  </section>
</div>
@endsection
