@extends('components.layout.admin')
@section('page_title','Semua Kursus')
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Semua Kursus</h2>
  </div>
  <x-ui.table>
    <x-slot name="header">
      <tr>
        <th class="text-left py-3 px-4">Judul</th>
        <th class="text-left py-3 px-4">Mentor</th>
        <th class="text-left py-3 px-4">Kategori</th>
        <th class="text-center py-3 px-4">Harga</th>
        <th class="text-center py-3 px-4">Verifikasi</th>
        <th class="text-center py-3 px-4">Siswa</th>
        <th class="text-center py-3 px-4">Status</th>
        <th class="text-center py-3 px-4">Dibuat</th>
      </tr>
    </x-slot>
    @forelse($courses as $c)
    <tr class="border-b border-white/10 hover:bg-white/5">
      <td class="py-3 px-4">
        <div class="flex items-center gap-3">
          <img src="{{ $c->thumbnail_url ?? 'https://placehold.co/60x40' }}" class="w-16 h-10 rounded object-cover" alt="Thumb">
          <span>{{ $c->title }}</span>
        </div>
      </td>
      <td class="py-3 px-4">{{ optional($c->author)->name }}</td>
      <td class="py-3 px-4">{{ optional($c->category)->name }}</td>
      <td class="text-center py-3 px-4">Rp {{ number_format($c->price,0,',','.') }}</td>
      <td class="text-center py-3 px-4">{{ ucfirst($c->verification_status ?? 'pending') }}</td>
      <td class="text-center py-3 px-4">{{ (int)($c->enrollments_count ?? 0) }}</td>
      <td class="text-center py-3 px-4">{{ ucfirst($c->status) }}</td>
      <td class="text-center py-3 px-4">{{ optional($c->created_at)->format('d M Y') }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="6" class="py-8 px-4 text-center text-white/60">Tidak ada kursus.</td>
    </tr>
    @endforelse
  </x-ui.table>
  @if($courses->hasPages())
    <div class="p-4">{{ $courses->links() }}</div>
  @endif
</div>
@endsection
