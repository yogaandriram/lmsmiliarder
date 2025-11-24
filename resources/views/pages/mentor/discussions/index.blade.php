@extends('components.layout.mentor')
@section('page_title','Diskusi')
@section('content')
<div class="space-y-8">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-bold">Grup Diskusi Kursus</h2>
  </div>

  <div class="glass p-6 rounded-lg">
    @if($groups->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($groups as $group)
          <div class="glass p-5 rounded-lg flex flex-col gap-3">
            <div>
              <div class="text-sm text-white/70">Kursus</div>
              <h3 class="text-lg font-semibold">{{ $group->course->title }}</h3>
              <div class="text-xs text-white/60">Grup: {{ $group->group_name }}</div>
            </div>
            <div class="flex items-center justify-between text-sm">
              <div class="text-white/70">Dibuat: 
                @php $created = $group->created_at ?? null; @endphp
                <span class="text-white/90">{{ $created ? \Illuminate\Support\Carbon::parse($created)->format('d M Y') : '-' }}</span>
              </div>
              <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full">Aktif</span>
            </div>
              <div class="flex justify-between items-center mt-2">
                <div class="text-sm text-white/70">Siswa: <span class="font-semibold">{{ $group->course->enrollments_count }}</span></div>
              <x-ui.btn-secondary href="{{ route('mentor.discussions.chat', $group) }}" size="sm" icon="fa-solid fa-comments">Selengkapnya</x-ui.btn-secondary>
              </div>
            </div>
          @endforeach
      </div>
    @else
      <div class="text-center py-10 text-white/60">
        <i class="fa-solid fa-comments text-3xl mb-3"></i>
        <div>Belum ada grup diskusi. Buat kursus untuk mengaktifkan diskusi.</div>
      </div>
    @endif
  </div>
</div>
@endsection