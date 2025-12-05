@extends('components.layout.app')
@section('page_title', $current ? ($current->title ?? 'Belajar Kursus') : 'Belajar Kursus')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
  <aside class="md:col-span-1 glass p-4 rounded">
    <div class="flex items-center justify-between mb-3">
      <div class="font-semibold">Course Content</div>
      <x-ui.navbar.theme-toggle />
    </div>
    <div class="space-y-4">
      @foreach($course->modules->sortBy('order') as $mod)
        <div>
          <div class="text-white/80 font-medium mb-2">{{ $mod->title }}</div>
          <div class="space-y-1">
            @foreach($mod->lessons->sortBy('order') as $les)
              <a href="{{ route('member.courses.learn', [$course, 'lesson' => $les->id]) }}"
                 class="flex items-center justify-between px-3 py-2 rounded bg-white/5 hover:bg-white/10 {{ ($current && $current->id===$les->id)?'ring-1 ring-yellow-400':'' }}">
                <span class="text-sm">{{ $les->title }}</span>
                <span class="text-xs text-white/60">{{ $les->duration_minutes ? $les->duration_minutes.'m' : '' }}</span>
              </a>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </aside>

  <section class="md:col-span-3 space-y-6">
    <div class="glass p-4 rounded">
      <div class="aspect-video w-full rounded overflow-hidden bg-black/30 relative">
        @if($current && $current->video_url)
          <iframe src="{{ $current->video_url }}" class="w-full h-full" allowfullscreen></iframe>
        @else
          <img src="https://images.unsplash.com/photo-1518976024611-4881f04e2d34?q=80&w=1200&auto=format&fit=crop" class="w-full h-full object-cover" alt="Video placeholder" />
          <div class="absolute inset-0 flex items-center justify-center">
            <x-ui.btn-primary icon="fa-solid fa-play">Play</x-ui.btn-primary>
          </div>
        @endif
      </div>
      <div class="flex items-center gap-2 mt-3">
        <div class="flex-1 h-2 bg-white/10 rounded overflow-hidden">
          <div class="h-2 bg-yellow-400" style="width: {{ $progress }}%;"></div>
        </div>
        <div class="text-sm text-white/70">{{ $progress }}%</div>
      </div>
    </div>

    <div class="space-y-2">
      <h1 class="text-2xl md:text-3xl font-bold">{{ $current ? ($current->title ?? $course->title) : $course->title }}</h1>
      <div class="text-sm text-white/60">Lesson {{ $currentIndex }} of {{ $total }} | Modul: {{ $current && $current->module ? $current->module->title : '-' }}</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
      <div class="md:col-span-2 glass p-4 rounded">
        <h3 class="text-lg font-semibold text-yellow-400 mb-2">Description</h3>
        <div class="prose prose-invert max-w-none">
          {!! $current && $current->content ? nl2br(e($current->content)) : 'Belum ada deskripsi.' !!}
        </div>
      </div>
      <div class="glass p-4 rounded">
        <h3 class="text-lg font-semibold text-yellow-400 mb-2">Lesson Status</h3>
        <div class="text-sm text-white/80">Lesson Incomplete</div>
        <div class="text-xs text-white/60">Tonton minimal 80% video untuk menyelesaikan.</div>
      </div>
    </div>
  </section>
</div>
@endsection
