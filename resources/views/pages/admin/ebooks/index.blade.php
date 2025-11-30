@extends('components.layout.admin')
@section('page_title','Semua E-book')
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Semua E-book</h2>
  </div>
  <x-ui.table>
    <x-slot name="header">
      <tr>
        <th class="text-left py-3 px-4">Judul</th>
        <th class="text-left py-3 px-4">Mentor</th>
        <th class="text-center py-3 px-4">Harga</th>
        <th class="text-center py-3 px-4">Status</th>
        <th class="text-center py-3 px-4">Verifikasi</th>
        <th class="text-center py-3 px-4">Dibuat</th>
      </tr>
    </x-slot>
    @forelse($ebooks as $e)
    <tr class="border-b border-white/10 hover:bg-white/5">
      <td class="py-3 px-4">
        <div class="flex items-center gap-3">
          <img src="{{ $e->cover_image_url ?? 'https://placehold.co/60x80' }}" class="w-12 h-16 rounded object-cover" alt="Cover">
          <span>{{ $e->title }}</span>
        </div>
      </td>
      <td class="py-3 px-4">{{ optional($e->author)->name }}</td>
      <td class="text-center py-3 px-4">Rp {{ number_format($e->price,0,',','.') }}</td>
      <td class="text-center py-3 px-4">{{ ucfirst($e->status) }}</td>
      <td class="text-center py-3 px-4">{{ ucfirst($e->verification_status ?? 'pending') }}</td>
      <td class="text-center py-3 px-4">{{ optional($e->created_at)->format('d M Y') }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="6" class="py-8 px-4 text-center text-white/60">Tidak ada e-book.</td>
    </tr>
    @endforelse
  </x-ui.table>
  @if($ebooks->hasPages())
    <div class="p-4">{{ $ebooks->links() }}</div>
  @endif
</div>
@endsection
