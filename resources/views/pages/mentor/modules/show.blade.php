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

  <!-- Quiz Modul -->
  <div class="glass p-6 rounded-lg">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-yellow-400">Kuis Modul</h3>
      @if(!$module->quiz)
        <x-ui.btn-primary size="sm" icon="fa-solid fa-plus" onclick="toggleModal('modalAddQuiz')">Tambah Kuis</x-ui.btn-primary>
      @endif
    </div>
    @if(session('success'))
      <div class="mb-3 p-3 bg-green-500/10 text-green-300 rounded">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
      <div class="mb-3 p-3 bg-yellow-500/10 text-yellow-300 rounded">{{ session('warning') }}</div>
    @endif
    @if($module->quiz)
      <div class="p-4 bg-white/5 rounded flex items-center justify-between">
        <div>
          <div class="font-medium">{{ $module->quiz->title }}</div>
          <div class="text-sm text-white/70">Batas waktu: {{ $module->quiz->time_limit_minutes ?? '-' }} menit</div>
        </div>
        <div class="text-white/60 text-sm">Satu kuis per modul</div>
      </div>
      <div class="mt-4">
        <h4 class="text-md font-semibold mb-2">Pertanyaan Kuis</h4>
        @php $questions = \App\Models\QuizQuestion::where('quiz_id', $module->quiz->id)->with('options')->orderBy('question_order')->get(); @endphp
        @if($questions->count())
          <div class="space-y-2">
            @foreach($questions as $q)
              <div class="p-3 bg-white/5 rounded">
                <div class="font-medium">{{ $q->question_order }}. {{ $q->question_text }}</div>
                @if($q->question_type === 'multiple_choice')
                  <ul class="mt-2 space-y-1">
                    @foreach($q->options as $opt)
                      <li class="flex items-center gap-2">
                        @if($opt->is_correct)
                          <i class="fa-solid fa-check text-green-400"></i>
                        @else
                          <i class="fa-solid fa-circle text-white/40"></i>
                        @endif
                        <span>{{ $opt->option_text }}</span>
                      </li>
                    @endforeach
                  </ul>
                @endif
              </div>
            @endforeach
          </div>
        @else
          <p class="text-white/60">Belum ada pertanyaan.</p>
        @endif
        <x-ui.btn-primary size="sm" icon="fa-solid fa-plus" onclick="toggleModal('modalAddQuestion')" class="mt-2">Tambah Pertanyaan</x-ui.btn-primary>
      </div>
    @else
      <p class="text-white/60">Belum ada kuis untuk modul ini.</p>
    @endif
  </div>
  </div>

  <!-- Modal Tambah Pelajaran (fullscreen overlay) -->
  <div id="modalAddLesson" class="fixed inset-0 w-screen h-screen bg-black/60 z-9999 hidden place-items-center">
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
    <div id="modalEditLesson{{ $lesson->id }}" class="fixed inset-0 w-screen h-screen bg-black/60 z-9999 hidden place-items-center">
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
  <!-- Modal Tambah Kuis -->
  <div id="modalAddQuiz" class="fixed inset-0 w-screen h-screen bg-black/60 z-9999 hidden place-items-center">
    <div class="glass p-6 rounded w-full max-w-lg shadow-xl ring-1 ring-white/20">
      <h4 class="text-lg font-semibold mb-4">Tambah Kuis Modul</h4>
      <form method="POST" action="{{ route('mentor.courses.modules.quiz.store', [$course, $module]) }}" class="space-y-4">
        @csrf
        <x-ui.crud.input label="Judul Kuis" name="title" required variant="glass" />
        <x-ui.crud.textarea label="Deskripsi" name="description" variant="glass"></x-ui.crud.textarea>
        <x-ui.crud.input label="Batas Waktu (menit)" name="time_limit_minutes" type="number" min="0" variant="glass" />
        <div class="flex gap-2 justify-end">
          <x-ui.btn-secondary type="button" onclick="toggleModal('modalAddQuiz')">Batal</x-ui.btn-secondary>
          <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Simpan</x-ui.btn-primary>
        </div>
      </form>
    </div>
  </div>
  <!-- Modal Tambah Pertanyaan -->
  <div id="modalAddQuestion" class="fixed inset-0 w-screen h-screen bg-black/60 z-9999 hidden place-items-center">
    <div class="glass p-6 rounded w-full max-w-lg shadow-xl ring-1 ring-white/20">
      <h4 class="text-lg font-semibold mb-4">Tambah Pertanyaan Kuis</h4>
      <form method="POST" action="{{ route('mentor.courses.modules.quiz.questions.store', [$course,$module]) }}" class="space-y-4">
        @csrf
        <x-ui.crud.textarea label="Pertanyaan" name="question_text" variant="glass"></x-ui.crud.textarea>
        <label class="block text-sm text-white/80">Tipe Pertanyaan</label>
        <select name="question_type" id="question_type_select" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded text-white">
          <option value="multiple_choice">Pilihan Ganda</option>
          <option value="essay">Essay</option>
        </select>
        <div id="mc_fields" class="space-y-2">
          @for($i=0;$i<4;$i++)
            <div class="flex items-center gap-2">
              <input type="text" name="options[{{ $i }}][text]" class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded text-white" placeholder="Opsi {{ $i+1 }}" />
              <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" name="options[{{ $i }}][is_correct]" class="rounded"> Benar
              </label>
            </div>
          @endfor
        </div>
        <div class="flex gap-2 justify-end">
          <x-ui.btn-secondary type="button" onclick="toggleModal('modalAddQuestion')">Batal</x-ui.btn-secondary>
          <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Simpan</x-ui.btn-primary>
        </div>
      </form>
    </div>
  </div>
  <script>
  (function(){
    var sel = document.getElementById('question_type_select');
    var mc = document.getElementById('mc_fields');
    if(sel && mc){
      var sync = function(){ mc.style.display = sel.value === 'multiple_choice' ? '' : 'none'; };
      sel.addEventListener('change', sync);
      sync();
    }
    window.toggleModal = function(id){
      var el = document.getElementById(id);
      if(!el) return;
      var hidden = el.classList.contains('hidden');
      if(hidden){ el.classList.remove('hidden'); el.classList.add('grid'); }
      else { el.classList.add('hidden'); el.classList.remove('grid'); }
    };
  })();
  </script>
