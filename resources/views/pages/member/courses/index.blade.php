@extends('components.layout.member')
@section('page_title','Kursus Saya')
@section('content')
<div class="space-y-6">
  <h2 class="text-3xl md:text-4xl font-bold">Kursus yang Dimiliki</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($enrollments as $en)
      @php $course = $en->course; @endphp
      <div class="glass p-4 rounded overflow-hidden">
        <div class="aspect-square w-full mb-3 bg-white/5 rounded overflow-hidden">
          @php $thumb = $course->thumbnail_url ?? null; @endphp
          @if($course && !empty($thumb))
            <img src="{{ $thumb }}" alt="{{ $course->title }}" class="w-full h-full object-cover" />
          @else
            <img src="https://placehold.co/300x300?text=Course" alt="No Image" class="w-full h-full object-cover" />
          @endif
        </div>
        <div class="space-y-3">
          <div class="font-semibold text-yellow-300">{{ $course->title ?? 'Kursus' }}</div>
          <div class="text-sm text-white/70">Enrolled: {{ optional($en->enrolled_at)->format('d M Y') }}</div>
          <div>
            <x-ui.btn-primary href="{{ route('member.courses.learn', $course) }}" icon="fa-solid fa-play">Akses Sekarang</x-ui.btn-primary>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-1 md:col-span-3 text-center text-white/60 py-12">Belum ada kursus dengan status pembayaran sukses.</div>
    @endforelse
  </div>
</div>
@endsection
