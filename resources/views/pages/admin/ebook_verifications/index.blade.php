@extends('components.layout.admin')
@section('page_title', 'Verifikasi E-book')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Verifikasi E-book (Pending)</h2>
  </div>

  <x-ui.table>
    <x-slot name="header">
      <tr>
        <th class="text-left py-3 px-4">E-book</th>
        <th class="text-left py-3 px-4">Mentor</th>
        <th class="text-center py-3 px-4">Harga</th>
        <th class="text-center py-3 px-4">Status</th>
        <th class="text-right py-3 px-4">Aksi</th>
      </tr>
    </x-slot>
    @forelse($ebooks as $ebook)
    <tr class="border-b border-white/10 hover:bg-white/5">
      <td class="py-3 px-4">
        <div class="flex items-center gap-3">
          <img src="{{ $ebook->cover_image_url ?? 'https://placehold.co/60x80' }}" class="w-12 h-16 rounded object-cover" alt="Cover">
          <div>
            <div class="font-medium">{{ $ebook->title }}</div>
            <div class="text-sm text-white/70">Slug: {{ $ebook->slug }}</div>
          </div>
        </div>
      </td>
      <td class="py-3 px-4">
        <div class="font-medium">{{ $ebook->author->name }}</div>
        <div class="text-sm text-white/70">{{ $ebook->author->email }}</div>
      </td>
      <td class="text-center py-3 px-4">Rp {{ number_format($ebook->price, 0, ',', '.') }}</td>
      <td class="text-center py-3 px-4">
        @if($ebook->status == 'published')
          <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full">Aktif</span>
        @elseif($ebook->status == 'draft')
          <span class="px-2 py-1 bg-gray-500/20 text-gray-300 text-xs rounded-full">Draft</span>
        @else
          <span class="px-2 py-1 bg-orange-500/20 text-orange-300 text-xs rounded-full">Arsip</span>
        @endif
      </td>
      <td class="text-right py-3 px-4">
        <div class="flex gap-2 justify-end">
          <x-ui.btn-secondary href="{{ route('admin.ebook_verifications.show', $ebook) }}" size="sm" icon="fa-solid fa-eye">Lihat</x-ui.btn-secondary>
          <form method="POST" action="{{ route('admin.ebook_verifications.approve', $ebook) }}" class="inline">
            @csrf
            <x-ui.btn-primary type="submit" size="sm" icon="fa-solid fa-check">Setujui</x-ui.btn-primary>
          </form>
          <form method="POST" action="{{ route('admin.ebook_verifications.reject', $ebook) }}" class="inline">
            @csrf
            <x-ui.btn-primary type="submit" size="sm" variant="danger" icon="fa-solid fa-xmark">Tolak</x-ui.btn-primary>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="5" class="py-8 px-4 text-center text-white/60">Tidak ada e-book pending verifikasi.</td>
    </tr>
    @endforelse
  </x-ui.table>
</div>
@endsection

