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
            @if($mod->quiz)
              <div class="flex items-center justify-between px-3 py-2 rounded bg-white/5">
                <div class="flex items-center gap-2 text-sm">
                  <span>Kuis Modul</span>
                  @if($current && $current->module && $mod->id === $current->module->id && !$moduleCompleted)
                    <i class="fa-solid fa-lock text-white/60" title="Tonton semua video modul untuk membuka kuis"></i>
                  @endif
                </div>
                <span class="text-xs text-white/60">{{ $mod->quiz->time_limit_minutes ? $mod->quiz->time_limit_minutes.'m' : '' }}</span>
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </aside>

  <section class="md:col-span-3 space-y-6">
    <div class="glass p-4 rounded">
      <div class="aspect-video w-full rounded overflow-hidden bg-black/30 relative">
        @php
          $url = $current ? ($current->video_url ?? '') : '';
          $embed = null;
          if ($url) {
            if (preg_match('/youtu\.be\/(\w+)/', $url, $m)) {
              $embed = 'https://www.youtube.com/embed/'.$m[1].'?rel=0&modestbranding=1';
            } elseif (preg_match('/youtube\.com\/(?:watch\?v=|embed\/)([\w-]+)/', $url, $m)) {
              $embed = 'https://www.youtube.com/embed/'.$m[1].'?rel=0&modestbranding=1';
            } elseif (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
              $embed = 'https://player.vimeo.com/video/'.$m[1];
            }
          }
        @endphp
        @if($current && $url)
          @if($embed)
            <iframe src="{{ $embed }}" class="w-full h-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
          @else
            <video class="w-full h-full" controls preload="metadata">
              <source src="{{ \Illuminate\Support\Str::startsWith($url, ['http://','https://']) ? $url : asset($url) }}" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          @endif
        @else
          <img src="https://images.unsplash.com/photo-1518976024611-4881f04e2d34?q=80&w=1200&auto=format&fit=crop" class="w-full h-full object-cover" alt="Video placeholder" />
          <div class="absolute inset-0 flex items-center justify-center">
            <x-ui.btn-primary icon="fa-solid fa-play">Play</x-ui.btn-primary>
          </div>
        @endif
      </div>
      <div class="flex items-center gap-2 mt-3" data-progress-container data-progress="{{ (int)$progress }}">
        <div class="flex-1 h-2 bg-white/10 rounded overflow-hidden">
          <div class="h-2 bg-yellow-400" data-progress-bar></div>
        </div>
        <div class="text-sm text-white/70">{{ (int)$progress }}%</div>
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
        <div class="text-sm text-white opacity-80">Lesson Incomplete</div>
        <div class="text-xs text-white opacity-60">Tonton minimal 80% video untuk menyelesaikan.</div>
      </div>
    </div>
    
  </section>
</div>
<script>
(function(){
  var wrap = document.querySelector('[data-progress-container]');
  if(!wrap) return;
  var val = parseInt(wrap.getAttribute('data-progress')||'0',10);
  val = Math.max(0, Math.min(100, isNaN(val)?0:val));
  var bar = wrap.querySelector('[data-progress-bar]');
  if(bar){ bar.style.width = val + '%'; }
})();
</script>
@endsection
