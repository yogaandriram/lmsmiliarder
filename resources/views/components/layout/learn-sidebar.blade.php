@props(['course' => null, 'current' => null])
@php
  $courseVar = $course;
  $currentLesson = $current;
@endphp

<div class="space-y-4">
  <div class="px-2">
    <div class="flex items-center justify-between mb-2">
      <div class="font-semibold">Course Content</div>
      <x-ui.navbar.theme-toggle />
    </div>
    <div class="space-y-4">
      @if($courseVar)
        @foreach($courseVar->modules->sortBy('order') as $mod)
          <div>
            <button type="button" class="w-full flex items-center justify-between text-white/80 font-medium mb-2"
                    data-mod-toggle="mod_{{ $mod->id }}">
              <span>{{ $mod->title }}</span>
              <i class="fa-solid fa-chevron-right text-white/50" data-mod-icon></i>
            </button>
            @php
              $isOpen = $currentLesson && $currentLesson->module && $currentLesson->module->id === $mod->id;
            @endphp
            <div id="mod_{{ $mod->id }}" class="space-y-1 {{ $isOpen ? '' : 'hidden' }}">
              @foreach($mod->lessons->sortBy('order') as $les)
                <a href="{{ route('member.courses.learn', [$courseVar, 'lesson' => $les->id]) }}"
                   class="flex items-center justify-between px-3 py-2 rounded bg-white/5 hover:bg-white/10 {{ ($currentLesson && $currentLesson->id===$les->id)?'ring-1 ring-yellow-400':'' }}">
                  <span class="text-sm flex items-center gap-2"><i class="fa-solid fa-clapperboard text-white/60"></i>{{ $les->title }}</span>
                  <span class="text-xs text-white/60">{{ $les->duration_minutes ? $les->duration_minutes.'m' : '' }}</span>
                </a>
              @endforeach
              @if($mod->quiz)
                @php
                  $attempt = \App\Models\UserQuizAttempt::where('user_id', auth()->id())
                    ->where('quiz_id', $mod->quiz->id)
                    ->orderByDesc('completed_at')
                    ->first();
                  $moduleLessons = $mod->lessons()->orderBy('order')->get();
                  $moduleTotal = max(1, $moduleLessons->count());
                  $moduleIndex = 0;
                  if ($currentLesson && $currentLesson->module && $currentLesson->module->id === $mod->id) {
                      $moduleIndex = (int) $moduleLessons->search(function($l) use ($currentLesson){ return $l->id === $currentLesson->id; }) + 1;
                  }
                  $moduleProgress = $moduleIndex ? round($moduleIndex * 100 / $moduleTotal) : 0;
                @endphp
                <div class="space-y-2">
                  <div class="flex items-center justify-between px-3 py-2 rounded bg-white/5">
                    <div class="flex items-center gap-2 text-sm">
                      <span class="flex items-center gap-2"><i class="fa-solid fa-file-pen text-white/60"></i>Final Quiz</span>
                      @if($attempt)
                        <a href="{{ route('member.courses.modules.quiz.show', [$courseVar, $mod]) }}" class="text-green-300 underline">Lihat Nilai ({{ (int)$attempt->score }}%)</a>
                      @else
                        <a href="{{ route('member.courses.modules.quiz.show', [$courseVar, $mod]) }}" class="text-yellow-300 underline">Mulai</a>
                      @endif
                    </div>
                    <div class="flex items-center gap-2 text-xs text-white/60">
                      <span>{{ $mod->quiz->time_limit_minutes ? $mod->quiz->time_limit_minutes.'m' : '' }}</span>
                      @if($attempt)
                        <i class="fa-solid fa-lock"></i>
                      @endif
                    </div>
                  </div>
                  <div class="px-3">
                    <div class="h-[3px] bg-white/10 rounded-full overflow-hidden">
                      <div class="h-[3px] bg-yellow-400" data-mod-progress="{{ $moduleProgress }}"></div>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      @else
        <div class="text-white/60 text-sm">Tidak ada konten.</div>
      @endif
    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  document.querySelectorAll('[data-mod-toggle]').forEach(function(btn){
    btn.addEventListener('click', function(){
      var id = btn.getAttribute('data-mod-toggle');
      var panel = document.getElementById(id);
      if(!panel) return;
      var icon = btn.querySelector('[data-mod-icon]');
      var isHidden = panel.classList.contains('hidden');
      if(isHidden){ panel.classList.remove('hidden'); icon && icon.classList.add('rotate-90'); }
      else { panel.classList.add('hidden'); icon && icon.classList.remove('rotate-90'); }
    });
  });
  // apply progress widths
  document.querySelectorAll('[data-mod-progress]').forEach(function(el){
    var v = parseInt(el.getAttribute('data-mod-progress')||'0',10);
    if(isNaN(v) || v < 0) v = 0; if(v > 100) v = 100;
    el.style.width = v + '%';
  });
})();
</script>
@endpush
