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
          <div class="max-w-lg">
            <form action="{{ route('home') }}" method="GET">
                <x-ui.search name="q" placeholder="Cari kursus atau e-book" value="{{ request('q') }}" />
            </form>
          </div>
          <div class="flex gap-3">
            <a href="#courses" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-400 text-black rounded shadow"><i class="fa-solid fa-graduation-cap"></i><span>Jelajahi Kursus</span></a>
            <a href="#ebooks" class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/15 rounded"><i class="fa-solid fa-book"></i><span>E-book</span></a>
          </div>
        </div>
        
      </div>
    </div>
  </section>

  @php
    $q = request('q');
    
    $coursesQuery = \App\Models\Course::where('status','published');
    if($q) {
        $coursesQuery->where('title', 'like', '%'.$q.'%');
    }
    $courses = $coursesQuery->with(['author','category'])
      ->withCount(['enrollments','modules'])
      ->withSum('lessons as total_duration', 'duration_minutes')
      ->latest()->take(6)->get();

    $ebooksQuery = \App\Models\Ebook::where('status','published')
      ->where('verification_status','approved');
    if($q) {
        $ebooksQuery->where('title', 'like', '%'.$q.'%');
    }
    $ebooks = $ebooksQuery->with('author')
      ->withCount('transactionDetails')
      ->latest()->take(6)->get();
  @endphp

  <section id="courses" class="space-y-4">
    <h2 class="text-2xl font-semibold">Kursus Terbaru</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @forelse($courses as $c)
        <div class="glass p-5 rounded-2xl h-full flex flex-col group hover:ring-1 hover:ring-yellow-500/50 transition-all duration-300 relative overflow-hidden">
            <!-- Image with Overlay -->
            <div class="relative mb-4 rounded-xl overflow-hidden aspect-square">
                <img src="{{ $c->thumbnail_url ?? 'https://placehold.co/480x480' }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" alt="{{ $c->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80"></div>
                
                <!-- Category Badge -->
                <div class="absolute top-3 left-3 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-medium text-white ring-1 ring-white/20">
                    {{ $c->category->name ?? 'General' }}
                </div>
            </div>
            
            <div class="flex-1 flex flex-col">
                <!-- Title & Mentor -->
                <h3 class="text-xl font-bold text-white leading-snug mb-2 line-clamp-2 group-hover:text-yellow-400 transition-colors">
                    {{ $c->title }}
                </h3>
                
                <p class="text-sm text-gray-400 line-clamp-2 mb-3">{{ $c->description }}</p>
                
                <div class="flex items-center gap-2 mb-4">
                     @if($c->author->avatar_url)
                        <img src="{{ $c->author->avatar_url }}" alt="{{ $c->author->name }}" class="w-6 h-6 rounded-full object-cover ring-1 ring-yellow-500/50">
                     @else
                        <div class="w-6 h-6 rounded-full bg-linear-to-tr from-yellow-400 to-yellow-600 flex items-center justify-center text-[10px] font-bold text-black text-center box-border pt-0.5">
                            {{ substr($c->author->name ?? 'M', 0, 1) }}
                        </div>
                     @endif
                     <span class="text-sm text-gray-300">{{ $c->author->name ?? 'Mentor' }}</span>
                </div>

                <!-- Stats Row -->
                <div class="flex items-center gap-4 text-xs text-gray-400 mb-4 pb-4 border-b border-white/10">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-users text-yellow-500"></i>
                        <span>{{ $c->enrollments_count ?? 0 }} Siswa</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-clock text-yellow-500"></i>
                        @php
                            $min = $c->total_duration ?? 0;
                            $hours = floor($min / 60);
                            $minutes = $min % 60;
                            $durationText = $hours > 0 ? "{$hours} Jam {$minutes} Menit" : "{$minutes} Menit";
                        @endphp
                        <span>{{ $durationText }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-book-open text-yellow-500"></i>
                        <span>{{ $c->modules_count ?? 0 }} Modul</span>
                    </div>
                </div>
                
                <!-- Price & Action -->
                <div class="mt-auto flex flex-col gap-3">
                    <div class="flex items-end justify-between">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 mb-0.5">Harga</span>
                            <span class="text-lg font-bold text-yellow-400">Rp {{ number_format($c->price,0,',','.') }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                         <x-ui.btn-secondary size="sm" href="{{ route('public.courses.show.by_author', [\Illuminate\Support\Str::slug($c->author->name ?? 'mentor'), $c->slug]) }}" 
                             class="w-full justify-center bg-white/5 hover:bg-white/10 !border-white/10" icon="fa-solid fa-eye">
                             Detail
                         </x-ui.btn-secondary>
                         <x-ui.btn-primary size="sm" href="{{ route('checkout.course', $c) }}" 
                             class="w-full justify-center shadow-lg shadow-yellow-400/20" icon="fa-solid fa-cart-shopping">
                             Beli
                         </x-ui.btn-primary>
                    </div>
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
        <div class="glass p-5 rounded-2xl h-full flex flex-col group hover:ring-1 hover:ring-yellow-500/50 transition-all duration-300 relative overflow-hidden">
            <!-- Image with Overlay -->
            <div class="relative mb-4 rounded-xl overflow-hidden aspect-square">
                <img src="{{ $e->cover_image_url ?? 'https://placehold.co/480x480' }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" alt="{{ $e->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80"></div>
                
                <!-- Badge -->
                <div class="absolute top-3 left-3 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-medium text-white ring-1 ring-white/20">
                    E-book
                </div>
            </div>
            
            <div class="flex-1 flex flex-col">
                <!-- Title & Mentor -->
                <h3 class="text-xl font-bold text-white leading-snug mb-2 line-clamp-2 group-hover:text-yellow-400 transition-colors">
                    {{ $e->title }}
                </h3>
                
                <p class="text-sm text-gray-400 line-clamp-2 mb-3">{{ $e->description }}</p>

                <div class="flex items-center gap-2 mb-4">
                     @if($e->author->avatar_url)
                        <img src="{{ $e->author->avatar_url }}" alt="{{ $e->author->name }}" class="w-6 h-6 rounded-full object-cover ring-1 ring-yellow-500/50">
                     @else
                        <div class="w-6 h-6 rounded-full bg-linear-to-tr from-yellow-400 to-yellow-600 flex items-center justify-center text-[10px] font-bold text-black text-center box-border pt-0.5">
                            {{ substr($e->author->name ?? 'M', 0, 1) }}
                        </div>
                     @endif
                     <span class="text-sm text-gray-300">{{ $e->author->name ?? 'Mentor' }}</span>
                </div>

                <!-- Stats Row -->
                <div class="flex items-center gap-4 text-xs text-gray-400 mb-4 pb-4 border-b border-white/10">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-cart-shopping text-yellow-500"></i>
                        <span>{{ $e->transaction_details_count ?? 0 }} Terjual</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-file-pdf text-yellow-500"></i>
                        <span>PDF</span>
                    </div>
                </div>
                
                <!-- Price & Action -->
                <div class="mt-auto flex flex-col gap-3">
                    <div class="flex items-end justify-between">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 mb-0.5">Harga</span>
                            <span class="text-lg font-bold text-yellow-400">Rp {{ number_format($e->price,0,',','.') }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                         <x-ui.btn-secondary size="sm" href="{{ route('public.ebooks.show', $e->slug) }}" 
                             class="w-full justify-center bg-white/5 hover:bg-white/10 !border-white/10" icon="fa-solid fa-eye">
                             Detail
                         </x-ui.btn-secondary>
                         <x-ui.btn-primary size="sm" href="{{ route('checkout.ebook', $e) }}" 
                             class="w-full justify-center shadow-lg shadow-yellow-400/20" icon="fa-solid fa-cart-shopping">
                             Beli
                         </x-ui.btn-primary>
                    </div>
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
