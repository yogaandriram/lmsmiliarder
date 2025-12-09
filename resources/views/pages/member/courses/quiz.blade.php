@extends('components.layout.learn')
@section('page_title','Kuis Modul')
@section('content')
<div class="space-y-6" id="quizWrap" data-time-limit-seconds="{{ (int)($quiz->time_limit_minutes ?? 0) * 60 }}">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Kuis: {{ $quiz->title }}</h2>
    <div class="flex items-center gap-3">
      @if($quiz->time_limit_minutes)
        <div class="px-3 py-2 rounded bg-white/10 text-white/90" id="quizTimer" aria-live="polite">{{ sprintf('%02d:%02d', (int)$quiz->time_limit_minutes, 0) }}</div>
      @endif
      <a href="{{ route('member.courses.learn', [$course]) }}" class="px-3 py-2 rounded bg-white/10 text-white/80 hover:bg-white/20">Kembali</a>
    </div>
  </div>

  <div class="glass p-6 rounded">
    <form id="quizForm" method="POST" action="{{ route('member.courses.modules.quiz.submit', [$course, $module]) }}" class="space-y-4">
      @csrf
      @foreach($quiz->questions->sortBy('question_order') as $q)
        <div class="space-y-2">
          <div class="font-medium">Soal {{ $loop->iteration }}. </br> {{ $q->question_text }}</div>
          <div class="space-y-1">
            @foreach($q->options as $opt)
              <label class="flex items-center gap-2">
                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt->id }}" class="accent-yellow-400" />
                <span>{{ $opt->option_text }}</span>
              </label>
            @endforeach
          </div>
        </div>
        <hr class="border-white/10">
      @endforeach
      <div class="flex justify-end gap-2">
        <x-ui.btn-primary id="quizSubmitBtn" type="submit" icon="fa-solid fa-check">Selesai</x-ui.btn-primary>
      </div>
    </form>
  </div>
</div>
<script>
(function(){
  var wrap = document.getElementById('quizWrap');
  var form = document.getElementById('quizForm');
  var timerEl = document.getElementById('quizTimer');
  var submitBtn = document.getElementById('quizSubmitBtn');
  if(!wrap) return;
  var secs = parseInt(wrap.getAttribute('data-time-limit-seconds')||'0',10);
  if(isNaN(secs) || secs <= 0) return; // no timer
  function fmt(s){ var m = Math.floor(s/60), ss = s%60; return ("0"+m).slice(-2) + ':' + ("0"+ss).slice(-2); }
  timerEl && (timerEl.textContent = fmt(secs));
  var intv = setInterval(function(){
    secs -= 1;
    if(secs <= 0){
      clearInterval(intv);
      // lock inputs
      try { form.querySelectorAll('input').forEach(function(i){ i.disabled = true; }); } catch(e) {}
      if(submitBtn){ submitBtn.disabled = true; submitBtn.textContent = 'Mengirim...'; }
      form.submit();
      return;
    }
    if(timerEl){ timerEl.textContent = fmt(secs); }
  }, 1000);
})();
</script>
@endsection
