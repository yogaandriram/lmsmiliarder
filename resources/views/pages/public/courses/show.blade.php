@extends('components.layout.app')
@section('page_title', 'Kursus: ' . $course->title)

@section('content')
<div class="space-y-8">
  <div class="glass p-6 rounded-lg">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
      <div class="rounded-lg overflow-hidden bg-white/5 h-full">
        <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/800x450' }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
      </div>
      <div class="space-y-4 h-full">
        <h1 class="text-3xl md:text-4xl font-bold">{{ $course->title }}</h1>
        @php
          $publishedAt = $course->status === 'published' ? ($course->verified_at ?? $course->created_at) : null;
          $mentor = $course->author;
        @endphp
        <div class="text-sm text-white/70">
          <span>Dipublikasikan: {{ $publishedAt ? $publishedAt->format('d M Y') : '-' }}</span>
          <span class="mx-2">•</span>
          <span>Diperbarui: {{ $course->updated_at->format('d M Y') }}</span>
        </div>
        <div class="glass p-4 rounded">
          <h2 class="text-base font-semibold text-yellow-500 mb-2">Deskripsi Kursus</h2>
          <div class="prose prose-invert max-w-none">
            {!! nl2br(e($course->description)) !!}
          </div>
        </div>
        <div class="glass p-4 rounded">
          <h2 class="text-base font-semibold text-yellow-500 mb-2">Profil Mentor</h2>
          <div class="flex items-center gap-4">
            <img src="{{ optional($mentor)->avatar_url ?? 'https://placehold.co/64x64' }}" alt="Avatar Mentor" class="w-16 h-16 rounded-full object-cover">
            <div>
              <div class="font-semibold">{{ optional($mentor)->name ?? '-' }}</div>
              @if(optional($mentor)->job_title)
                <div class="text-sm text-white/70">{{ $mentor->job_title }}</div>
              @endif
            </div>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <x-ui.btn-secondary class="w-full justify-center" href="#detail" icon="fa-solid fa-eye">Lihat Selengkapnya</x-ui.btn-secondary>
          <x-ui.btn-primary class="w-full justify-center" href="#" icon="fa-solid fa-cart-shopping">Daftar Sekarang</x-ui.btn-primary>
        </div>
        <div class="my-4 border-t border-white/20"></div>
        <div class="grid grid-cols-2 gap-3">
          <x-ui.stat-card size="sm" label="Harga" value="Rp {{ number_format($course->price, 0, ',', '.') }}" icon="fa-solid fa-tag" />
          <x-ui.stat-card size="sm" label="Siswa" value="{{ (int)($course->enrollments_count ?? 0) }}" icon="fa-solid fa-users" />
          <x-ui.stat-card size="sm" label="Kategori" value="{{ $course->category->name ?? '-' }}" icon="fa-solid fa-layer-group" />
          <x-ui.stat-card size="sm" label="Durasi" value="{{ (int)($totalDurationMinutes ?? 0) }} menit" icon="fa-solid fa-clock" />
        </div>
      </div>
    </div>
  </div>



  @php
    $videoUrl = $course->intro_video_url ?? null;
    $embedUrl = null;
    if ($videoUrl && preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([A-Za-z0-9_-]+)/', $videoUrl, $m)) {
      $embedUrl = 'https://www.youtube.com/embed/'.$m[1];
    }
  @endphp
  @if($embedUrl)
    <div class="glass p-6 rounded-lg">
      <h2 class="text-lg font-semibold text-yellow-500 mb-3">Video Perkenalan</h2>
      <iframe class="w-full rounded" style="aspect-ratio:16/9" src="{{ $embedUrl }}" title="Intro Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
  @elseif($course->intro_video_url)
    <div class="glass p-6 rounded-lg">
      <h2 class="text-lg font-semibold text-yellow-500 mb-3">Video Perkenalan</h2>
      <x-ui.btn-secondary href="{{ $course->intro_video_url }}" icon="fa-solid fa-up-right-from-square" target="_blank">Buka Video</x-ui.btn-secondary>
    </div>
  @endif

  <div id="detail">
  <x-ui.table title="Kurikulum Kursus">
    <x-slot name="header">
      <tr>
        <th class="py-2">Modul</th>
        <th class="py-2">Pelajaran</th>
      </tr>
    </x-slot>
    @forelse($course->modules as $module)
      <tr class="border-t border-white/10">
        <td class="py-3 align-top">
          <div class="font-medium">{{ $module->title }}</div>
          <div class="text-xs text-white/70">{{ $module->lessons->count() }} pelajaran</div>
        </td>
        <td class="py-3">
          @if($module->lessons->count() > 0)
            <ul class="space-y-2">
              @foreach($module->lessons as $lesson)
                <li class="flex items-center gap-3">
                  <i class="fa-solid fa-play text-yellow-400"></i>
                  <div>
                    <div class="font-medium">{{ $lesson->title }}</div>
                    @if($lesson->duration_minutes)
                      <div class="text-xs text-white/70">Durasi: {{ $lesson->duration_minutes }} menit</div>
                    @endif
                  </div>
                </li>
              @endforeach
            </ul>
          @else
            <span class="text-sm text-white/60">Belum ada pelajaran.</span>
          @endif
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="2" class="py-8 text-center text-white/60">
          <i class="fa-solid fa-folder-open text-3xl mb-3"></i>
          <div>Belum ada modul.</div>
        </td>
      </tr>
    @endforelse
  </x-ui.table>
  </div>
</div>
@endsection