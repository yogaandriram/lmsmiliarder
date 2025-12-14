@extends('components.layout.app')

@section('page_title', 'Semua Kursus')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 items-start">
    <!-- Sidebar Filter -->
    <aside class="w-full lg:w-1/4 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sticky top-24 shadow-2xl">
        <h2 class="text-xl font-bold text-white mb-6 pb-4 border-b border-white/10">Filter By</h2>
        
        <div class="space-y-6">
            <!-- Search -->
            <div>
                <form action="{{ route('public.courses.index') }}" method="GET">
                    <x-ui.search name="q" placeholder="Cari..." value="{{ request('q') }}" />
                </form>
            </div>

            <!-- Category -->
            <div x-data="{ open: true }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-white font-medium mb-3">
                    <span>Category</span>
                    <i class="fa-solid fa-chevron-down transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>
                <div x-show="open" class="space-y-2">
                    @foreach($categories as $cat)
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="cat_{{ $cat->id }}" class="rounded bg-white/5 border-white/20 text-yellow-500 focus:ring-yellow-500/50">
                        <label for="cat_{{ $cat->id }}" class="text-sm text-gray-400 hover:text-white cursor-pointer select-none">{{ $cat->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tags -->
            <div x-data="{ open: true }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-white font-medium mb-3 pt-4 border-t border-white/5">
                    <span>Tags</span>
                    <i class="fa-solid fa-chevron-down transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>
                <div x-show="open" class="space-y-2">
                    @foreach($tags as $tag)
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="tag_{{ $tag->id }}" class="rounded bg-white/5 border-white/20 text-yellow-500 focus:ring-yellow-500/50">
                        <label for="tag_{{ $tag->id }}" class="text-sm text-gray-400 hover:text-white cursor-pointer select-none">{{ $tag->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>


        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-white">Semua Kursus</h1>
            <div class="relative">
                <button class="flex items-center gap-2 text-sm text-gray-400 bg-[#1A1A24] border border-white/10 px-4 py-2 rounded-lg hover:text-white hover:border-white/20 transition-all">
                    <span>Sorting By</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
            </div>
        </div>

        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $c)
                <div class="group relative flex flex-col h-full bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl hover:border-yellow-500/50 transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:-translate-y-1 overflow-hidden">
                    <!-- Image -->
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $c->thumbnail_url }}" alt="{{ $c->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-90"></div>
                        

                    </div>

                    <!-- Content -->
                    <div class="flex-1 p-5 flex flex-col">
                        <h3 class="text-lg font-bold text-white leading-snug mb-2 line-clamp-2 group-hover:text-yellow-400 transition-colors">
                            {{ $c->title }}
                        </h3>
                        <p class="text-xs text-gray-400 line-clamp-2 mb-4 leading-relaxed">{{ $c->description }}</p>

                        <!-- Stats Stats -->
                        <div class="flex items-center justify-between text-xs text-gray-400 mb-4 pb-4 border-b border-white/10">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-regular fa-clock"></i>
                                @php
                                    $min = $c->total_duration ?? 0;
                                    $hours = floor($min / 60);
                                    $minutes = $min % 60;
                                    $durationText = $hours > 0 ? "{$hours} Jam" : "{$minutes} Menit";
                                @endphp
                                <span>{{ $durationText }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-user-group"></i>
                                <span>{{ $c->enrollments_count ?? 0 }} Siswa</span>
                            </div>
                        </div>

                         <!-- Price -->
                        <div class="mb-4">
                            <div class="flex items-baseline gap-2">
                                @if($c->price == 0)
                                    <span class="text-lg font-bold text-green-400">Gratis</span>
                                @else
                                    <span class="text-lg font-bold text-yellow-500">Rp {{ number_format($c->price, 0, ',', '.') }}</span>
                                    <span class="text-xs text-gray-500 line-through">Rp {{ number_format($c->price * 1.5, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1">Mentor : {{ $c->author->name }}</p>
                        </div>

                        <!-- Action -->
                        <div class="mt-auto">
                            <x-ui.btn-primary href="{{ route('public.courses.show', $c->slug) }}" class="w-full justify-center !font-bold !py-2.5 !rounded-lg bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 border-none text-black shadow-lg shadow-yellow-500/20">
                                Mulai Belajar
                            </x-ui.btn-primary>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $courses->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white/5 rounded-2xl border border-white/10">
                <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-book-open text-2xl text-white/30"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Tidak ada kursus ditemukan</h3>
                <p class="text-white/50">Coba kata kunci lain atau reset filter pencarian Anda.</p>
            </div>
        @endif
    </main>
</div>
@endsection
