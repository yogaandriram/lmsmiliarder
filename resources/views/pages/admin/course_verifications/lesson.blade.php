@extends('components.layout.admin')
@section('page_title', 'Detail Pelajaran')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-2xl font-semibold">{{ $lesson->title }}</h2>
      <p class="text-white/70">Modul: {{ $lesson->module->title }} • Kursus: {{ $course->title }}</p>
    </div>
    <div class="flex gap-2">
      <x-ui.btn-secondary href="{{ route('admin.course_verifications.show', $course) }}" icon="fa-solid fa-arrow-left">Kembali ke Kursus</x-ui.btn-secondary>
      <form method="POST" action="{{ route('admin.course_verifications.approve', $course) }}">
        @csrf
        <x-ui.btn-primary type="submit" icon="fa-solid fa-check">Setujui Kursus</x-ui.btn-primary>
      </form>
    </div>
  </div>

  <div class="glass p-6 rounded-lg space-y-4">
    @php
      $videoUrl = $lesson->video_url;
      $embedUrl = null;
      if ($videoUrl) {
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([A-Za-z0-9_-]+)/', $videoUrl, $m)) {
          $embedUrl = 'https://www.youtube.com/embed/'.$m[1];
        }
      }
    @endphp
    @if($embedUrl)
      <div class="w-full">
        <iframe class="w-full rounded" style="aspect-ratio:16/9" src="{{ $embedUrl }}" title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>
    @elseif($lesson->video_url)
      <div class="text-white/80">
        <span class="font-medium">Video:</span>
        <a class="text-yellow-300 underline" href="{{ $lesson->video_url }}" target="_blank">{{ $lesson->video_url }}</a>
      </div>
    @endif

    <div class="text-white/80">
      <span class="font-medium">Durasi:</span> {{ $lesson->duration_minutes ?? '-' }} menit
    </div>
    <div class="text-white/80">
      <span class="font-medium">Konten:</span>
      <div class="mt-2 bg-white/5 p-4 rounded">{!! nl2br(e($lesson->content)) !!}</div>
    </div>
    @php
      $files = is_array($lesson->material_files) ? $lesson->material_files : [];
      if(!$files && $lesson->material_file_url){ $files = [$lesson->material_file_url]; }
    @endphp
    @if($files)
      <div class="text-white/80">
        <span class="font-medium">File Materi:</span>
        <div class="mt-2 space-y-2">
          @foreach($files as $i => $url)
            @php
              $path = parse_url($url, PHP_URL_PATH);
              $filename = $path ? basename($path) : ('Lampiran '.($i+1));
            @endphp
            <div class="flex items-center justify-between bg-white/5 p-3 rounded">
              <span class="text-white/80">{{ $filename }}</span>
              <a href="{{ $url }}" target="_blank" class="text-yellow-300 underline">Unduh</a>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</div>
@endsection