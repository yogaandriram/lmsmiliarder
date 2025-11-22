@extends('components.layout.mentor')
@section('page_title', 'Detail Kursus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/120x80' }}" alt="{{ $course->title }}" class="w-20 h-14 rounded-lg object-cover">
            <div>
                <h2 class="text-2xl font-semibold">{{ $course->title }}</h2>
                <p class="text-white/70">{{ $course->category->name ?? 'Tidak ada kategori' }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <x-ui.btn-secondary href="{{ route('mentor.courses.index') }}" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
            <x-ui.btn-primary href="{{ route('mentor.courses.edit', $course) }}" icon="fa-solid fa-edit">Edit Kursus</x-ui.btn-primary>
        </div>
    </div>

    <!-- Course Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-yellow-300">Status</div>
            <div class="text-lg font-bold">
                @if($course->status == 'published')
                    <span class="text-green-400">Aktif</span>
                @elseif($course->status == 'draft')
                    <span class="text-gray-400">Draft</span>
                @else
                    <span class="text-orange-400">Arsip</span>
                @endif
            </div>
        </div>
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-yellow-300">Total Siswa</div>
            <div class="text-lg font-bold">{{ $course->enrollments_count }}</div>
        </div>
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-yellow-300">Harga</div>
            <div class="text-lg font-bold">Rp {{ number_format($course->price, 0, ',', '.') }}</div>
        </div>
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-yellow-300">Dibuat</div>
            <div class="text-lg font-bold">{{ $course->created_at->format('d M Y') }}</div>
        </div>
    </div>

    <!-- Course Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Basic Info -->
        <div class="lg:col-span-2 space-y-6">
            @php
              $videoUrl = $course->intro_video_url ?? null;
              $embedUrl = null;
              if ($videoUrl) {
                if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([A-Za-z0-9_-]+)/', $videoUrl, $m)) {
                  $embedUrl = 'https://www.youtube.com/embed/'.$m[1];
                }
              }
            @endphp
            @if($embedUrl)
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Video Perkenalan</h3>
                <iframe class="w-full rounded" style="aspect-ratio:16/9" src="{{ $embedUrl }}" title="Intro Video" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            @elseif($course->intro_video_url)
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Video Perkenalan</h3>
                <a class="text-yellow-300 underline" href="{{ $course->intro_video_url }}" target="_blank">{{ $course->intro_video_url }}</a>
            </div>
            @endif
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Deskripsi Kursus</h3>
                <div class="prose prose-invert max-w-none">
                    {!! nl2br(e($course->description)) !!}
                </div>
            </div>

            <!-- Modules -->
            <div class="glass p-6 rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-yellow-400">Modul Kursus</h3>
                    <x-ui.btn-primary size="sm" icon="fa-solid fa-plus" onclick="toggleModal('modalAddModule')">Tambah Modul</x-ui.btn-primary>
                </div>
                @if($course->modules->count() > 0)
                    <div class="space-y-3">
                        @foreach($course->modules as $module)
                            <div class="p-4 bg-white/5 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium">{{ $module->title }}</h4>
                                        <p class="text-sm text-white/70">{{ $module->lessons->count() }} pelajaran</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <x-ui.btn-secondary href="{{ route('mentor.courses.modules.show', [$course, $module]) }}" size="sm" icon="fa-solid fa-eye">Lihat</x-ui.btn-secondary>
                                        <x-ui.btn-secondary href="#" size="sm" icon="fa-solid fa-edit">Edit</x-ui.btn-secondary>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-white/60">
                        <i class="fa-solid fa-folder-open text-4xl mb-4"></i>
                        <p class="text-lg font-medium mb-2">Belum ada modul</p>
                        <p class="text-sm text-white/70 mb-4">Tambahkan modul untuk mengorganisir pelajaran Anda</p>
                        <x-ui.btn-primary icon="fa-solid fa-plus" onclick="toggleModal('modalAddModule')">Tambah Modul</x-ui.btn-primary>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Tags -->
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Tag Kursus</h3>
                @if($course->tags->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($course->tags as $tag)
                            <span class="px-3 py-1 bg-yellow-400/20 text-yellow-300 text-sm rounded-full">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-white/60 text-sm">Tidak ada tag</p>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <x-ui.btn-secondary href="#" icon="fa-solid fa-plus" class="w-full">Tambah Modul</x-ui.btn-secondary>
                    <x-ui.btn-secondary href="#" icon="fa-solid fa-chart-line" class="w-full">Lihat Analitik</x-ui.btn-secondary>
                    <x-ui.btn-secondary href="#" icon="fa-solid fa-users" class="w-full">Lihat Siswa</x-ui.btn-secondary>
                    <x-ui.btn-secondary href="#" icon="fa-solid fa-comments" class="w-full">Forum Diskusi</x-ui.btn-secondary>
                </div>
            </div>

            <!-- Course Settings -->
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Pengaturan Kursus</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-white/90">Status</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($course->status == 'published') bg-green-500/20 text-green-300
                            @elseif($course->status == 'draft') bg-gray-500/20 text-gray-300
                            @else bg-orange-500/20 text-orange-300 @endif">
                            {{ ucfirst($course->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/90">Dibuat</span>
                        <span class="text-white/70 text-sm">{{ $course->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/90">Diperbarui</span>
                        <span class="text-white/70 text-sm">{{ $course->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
</div>

<!-- Modal Tambah Modul -->
<div id="modalAddModule" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
  <div class="glass p-6 rounded w-full max-w-md">
    <h4 class="text-lg font-semibold mb-4">Tambah Modul</h4>
    <form method="POST" action="{{ route('mentor.courses.modules.store', $course) }}" class="space-y-3">
      @csrf
      <x-ui.crud.input label="Judul Modul" name="title" required variant="glass" />
      <div class="flex gap-2 justify-end">
        <x-ui.btn-secondary type="button" onclick="toggleModal('modalAddModule')">Batal</x-ui.btn-secondary>
        <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Simpan</x-ui.btn-primary>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  window.toggleModal = function(id){
    var el = document.getElementById(id); if(!el) return;
    el.classList.toggle('hidden');
    document.body.classList.toggle('overflow-hidden', !el.classList.contains('hidden'));
  }
})();
</script>
<script>
(function(){
  window.toggleModal = window.toggleModal || function(id){
    var el = document.getElementById(id); if(!el) return;
    var willShow = el.classList.contains('hidden');
    el.classList.toggle('hidden');
    if(willShow){ el.classList.add('grid','place-items-center'); document.body.classList.add('overflow-hidden'); }
    else { el.classList.remove('grid','place-items-center'); document.body.classList.remove('overflow-hidden'); }
  }
})();
</script>