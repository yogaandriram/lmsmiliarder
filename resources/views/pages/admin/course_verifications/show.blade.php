@extends('components.layout.admin')
@section('page_title', 'Detail Verifikasi Kursus')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-4">
      <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/120x80' }}" class="w-20 h-14 rounded object-cover" alt="Thumbnail">
      <div>
        <h2 class="text-2xl font-semibold">{{ $course->title }}</h2>
        <p class="text-white/70">Mentor: {{ $course->author->name }} • {{ $course->author->email }}</p>
      </div>
    </div>
    <div class="flex gap-2">
      <form method="POST" action="{{ route('admin.course_verifications.approve', $course) }}">
        @csrf
        <x-ui.btn-primary type="submit" icon="fa-solid fa-check">Setujui</x-ui.btn-primary>
      </form>
      <form method="POST" action="{{ route('admin.course_verifications.reject', $course) }}">
        @csrf
        <x-ui.btn-primary type="submit" variant="danger" icon="fa-solid fa-xmark">Tolak</x-ui.btn-primary>
      </form>
    </div>
  </div>

  @php
    $videoUrl = $course->intro_video_url ?? null;
    $embedUrl = null;
    if ($videoUrl) {
      if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([A-Za-z0-9_-]+)/', $videoUrl, $m)) {
        $embedUrl = 'https://www.youtube.com/embed/'.$m[1];
      }
    }
  @endphp

  @if($embedUrl || $course->intro_video_url)
  <div class="glass p-6 rounded-lg">
    <h3 class="text-lg font-semibold text-yellow-400 mb-4">Video Perkenalan</h3>
    @if($embedUrl)
      <iframe class="w-full rounded" style="aspect-ratio:16/9" src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    @else
      <a class="text-yellow-300 underline" href="{{ $course->intro_video_url }}" target="_blank">{{ $course->intro_video_url }}</a>
    @endif
  </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="glass p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-yellow-400 mb-4">Deskripsi Kursus</h3>
        <div class="prose prose-invert max-w-none">{!! nl2br(e($course->description)) !!}</div>
      </div>

      <div class="glass p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-yellow-400 mb-4">Modul</h3>
        @if($course->modules->count())
          <div class="space-y-3">
            @foreach($course->modules as $module)
              <div class="p-4 bg-white/5 rounded">
                <div class="flex items-center justify-between">
                  <div>
                    <div class="font-medium">{{ $module->title }}</div>
                    <div class="text-sm text-white/70">{{ $module->lessons->count() }} pelajaran</div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-white/60">Belum ada modul.</div>
        @endif
      </div>

      <div class="glass p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-yellow-400 mb-4">Pelajaran</h3>
        @php
          $lessons = collect($course->modules)->flatMap(fn($m) => $m->lessons->map(fn($l) => ['module' => $m, 'lesson' => $l]));
        @endphp
        @if($lessons->count())
          <x-ui.table>
            <x-slot name="header">
              <tr>
                <th class="text-left py-3 px-4">Modul</th>
                <th class="text-left py-3 px-4">Pelajaran</th>
                <th class="text-center py-3 px-4">Durasi</th>
                <th class="text-right py-3 px-4">Aksi</th>
              </tr>
            </x-slot>
            @foreach($lessons as $row)
              @php($m = $row['module'])
              @php($l = $row['lesson'])
              <tr class="border-b border-white/10 hover:bg-white/5">
                <td class="py-3 px-4">{{ $m->title }}</td>
                <td class="py-3 px-4">{{ $l->title }}</td>
                <td class="text-center py-3 px-4">{{ $l->duration_minutes ?? '-' }} menit</td>
                <td class="text-right py-3 px-4">
                  <x-ui.btn-secondary href="{{ route('admin.course_verifications.lessons.show', [$course, $l]) }}" size="sm" icon="fa-solid fa-eye">Lihat</x-ui.btn-secondary>
                </td>
              </tr>
            @endforeach
          </x-ui.table>
        @else
          <div class="text-white/60">Belum ada pelajaran.</div>
        @endif
      </div>
    </div>

    <div class="space-y-6">
      <div class="glass p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-yellow-400 mb-4">Info Kursus</h3>
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-white/90">Kategori</span>
            <span class="text-white/70">{{ $course->category->name ?? '-' }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-white/90">Harga</span>
            <span class="text-white/70">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-white/90">Status</span>
            <span class="text-white/70">{{ ucfirst($course->status) }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-white/90">Verifikasi</span>
            <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-300">{{ ucfirst($course->verification_status) }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection