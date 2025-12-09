@extends('components.layout.learn')
@section('page_title','Hasil Kuis')
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Hasil Kuis: {{ $quiz->title }}</h2>
    <a href="{{ route('member.courses.learn', [$course]) }}" class="px-3 py-2 rounded bg-white/10 text-white/80 hover:bg-white/20">Kembali ke Belajar</a>
  </div>

  @if(session('success'))
    <div class="glass p-4 rounded text-yellow-300">{{ session('success') }}</div>
  @elseif(session('warning'))
    <div class="glass p-4 rounded text-yellow-300">{{ session('warning') }}</div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-ui.stat-card label="Skor" :value="(string)($attempt->score).'%'" icon="fa-solid fa-star" />
    <x-ui.stat-card label="Dikerjakan" :value="optional($attempt->completed_at)->format('d M Y H:i')" icon="fa-solid fa-clock" />
    <x-ui.stat-card label="Durasi Batas" :value="($quiz->time_limit_minutes ? $quiz->time_limit_minutes.'m' : '-')" icon="fa-solid fa-hourglass-half" />
  </div>

  <div class="glass p-6 rounded">
    <h3 class="text-lg font-semibold text-yellow-400 mb-3">Ringkasan</h3>
    <p class="text-white/80">Anda sudah menyelesaikan kuis ini. Sesuai aturan, kuis tidak dapat dikerjakan ulang.</p>
  </div>
</div>
@endsection
