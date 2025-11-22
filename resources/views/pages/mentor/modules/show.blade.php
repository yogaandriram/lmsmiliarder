@extends('components.layout.mentor')
@section('page_title', 'Detail Modul')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-2xl font-semibold">{{ $module->title }}</h2>
      <p class="text-white/70">Kursus: {{ $course->title }}</p>
    </div>
    <div class="flex gap-2">
      <x-ui.btn-primary icon="fa-solid fa-plus" onclick="toggleModal('modalAddLesson')">Tambah Pelajaran</x-ui.btn-primary>
      <x-ui.btn-secondary href="{{ route('mentor.courses.show', $course) }}" icon="fa-solid fa-arrow-left">Kembali ke Kursus</x-ui.btn-secondary>
    </div>
  </div>

  <div class="glass p-6 rounded-lg">
    <h3 class="text-lg font-semibold text-yellow-400 mb-4">Pelajaran Modul</h3>
    @if($module->lessons->count())
      <div class="space-y-3">
        @foreach($module->lessons as $lesson)
          <div class="p-4 bg-white/5 rounded flex items-center justify-between">
            <div>
              <div class="font-medium">{{ $lesson->title }}</div>
              <div class="text-sm text-white/70">Durasi: {{ $lesson->duration_minutes ?? '-' }} menit</div>
            </div>
            <div class="flex items-center gap-2">
              <x-ui.btn-secondary href="{{ route('mentor.courses.modules.lessons.show', [$course,$module,$lesson]) }}" size="sm" icon="fa-solid fa-eye">Detail</x-ui.btn-secondary>
              <x-ui.btn-secondary size="sm" icon="fa-solid fa-pen" onclick="toggleModal('modalEditLesson{{ $lesson->id }}')">Edit</x-ui.btn-secondary>
              <form method="POST" action="{{ route('mentor.courses.modules.lessons.destroy', [$course,$module,$lesson]) }}" onsubmit="return confirm('Hapus pelajaran ini?')">
                @csrf
                @method('DELETE')
                <x-ui.btn-primary type="submit" size="sm" variant="danger" icon="fa-solid fa-trash">Hapus</x-ui.btn-primary>
              </form>
            </div>
          </div>

          
        @endforeach
      </div>
    @else
      <div class="text-center py-8 text-white/60">
        <i class="fa-solid fa-book-open text-4xl mb-4"></i>
        <p class="text-lg font-medium mb-2">Belum ada pelajaran</p>
        <p class="text-sm text-white/70 mb-4">Tambahkan pelajaran untuk modul ini</p>
        <x-ui.btn-primary icon="fa-solid fa-plus" onclick="toggleModal('modalAddLesson')">Tambah Pelajaran</x-ui.btn-primary>
      </div>
    @endif
  </div>
  </div>

  <!-- Modal Tambah Pelajaran (fullscreen overlay) -->
  <div id="modalAddLesson" class="fixed inset-0 w-screen h-screen bg-black/60 z-[9999] hidden grid place-items-center">
    <div class="glass p-6 rounded w-full max-w-lg shadow-xl ring-1 ring-white/20">
      <h4 class="text-lg font-semibold mb-4">Tambah Pelajaran</h4>
      <form method="POST" action="{{ route('mentor.courses.modules.lessons.store', [$course, $module]) }}" class="space-y-4" enctype="multipart/form-data">
        @csrf
        <x-ui.crud.input label="Judul Pelajaran" name="title" required variant="glass" />
        <x-ui.crud.textarea label="Konten" name="content" variant="glass"></x-ui.crud.textarea>
        <x-ui.crud.input label="URL Video" name="video_url" type="url" variant="glass" />
        <x-ui.crud.input label="Durasi (menit)" name="duration_minutes" type="number" variant="glass" />
        <x-ui.crud.input label="File Materi (PDF/DOC)" name="material_files[]" type="file" accept=".pdf,.doc,.docx" multiple variant="glass" />
        
        <div class="flex gap-2 justify-end">
          <x-ui.btn-secondary type="button" onclick="toggleModal('modalAddLesson')">Batal</x-ui.btn-secondary>
          <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Simpan</x-ui.btn-primary>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit Pelajaran (render outside list for fullscreen overlay) -->
  @foreach($module->lessons as $lesson)
    <div id="modalEditLesson{{ $lesson->id }}" class="fixed inset-0 w-screen h-screen bg-black/60 z-[9999] hidden grid place-items-center">
      <div class="glass p-6 rounded w-full max-w-lg shadow-xl ring-1 ring-white/20">
        <h4 class="text-lg font-semibold mb-4">Edit Pelajaran</h4>
        <form method="POST" action="{{ route('mentor.courses.modules.lessons.update', [$course,$module,$lesson]) }}" class="space-y-4" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <x-ui.crud.input label="Judul Pelajaran" name="title" :value="$lesson->title" required variant="glass" />
          <x-ui.crud.textarea label="Konten" name="content" variant="glass">{{ $lesson->content }}</x-ui.crud.textarea>
          <x-ui.crud.input label="URL Video" name="video_url" type="url" :value="$lesson->video_url" variant="glass" />
          <x-ui.crud.input label="Durasi (menit)" name="duration_minutes" type="number" :value="$lesson->duration_minutes" variant="glass" />
          <x-ui.crud.input label="File Materi (PDF/DOC)" name="material_files[]" type="file" accept=".pdf,.doc,.docx" multiple variant="glass" />
          <div class="flex gap-2 justify-end">
            <x-ui.btn-secondary type="button" onclick="toggleModal('modalEditLesson{{ $lesson->id }}')">Batal</x-ui.btn-secondary>
            <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Simpan</x-ui.btn-primary>
          </div>
        </form>
      </div>
    </div>
  @endforeach

@endsection

<script>
(function(){
  window.toggleModal = function(id){
    var el = document.getElementById(id); if(!el) return;
    var willShow = el.classList.contains('hidden');
    el.classList.toggle('hidden');
    if(willShow){
      el.classList.add('grid','place-items-center');
      document.body.classList.add('overflow-hidden');
    } else {
      el.classList.remove('grid','place-items-center');
      document.body.classList.remove('overflow-hidden');
    }
  }
})();
</script>
