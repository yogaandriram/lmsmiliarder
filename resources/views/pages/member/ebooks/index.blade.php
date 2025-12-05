@extends('components.layout.member')
@section('page_title','E-book Saya')

@section('content')
<div class="space-y-8">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">E-book Saya</h2>
  </div>

  <div class="glass p-6 rounded-lg">
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-3 px-4">Judul</th>
          <th class="text-left py-3 px-4">Mentor</th>
          <th class="text-center py-3 px-4">Dibeli</th>
          <th class="text-center py-3 px-4">Aksi</th>
        </tr>
      </x-slot>
      @forelse($items as $lib)
        @php $ebook = $lib->ebook; @endphp
        <tr class="border-b border-white/10 hover:bg-white/5">
          <td class="py-3 px-4">
            <div class="flex items-center gap-3">
              <img src="{{ optional($ebook)->cover_image_url ?? 'https://placehold.co/60x80' }}" class="w-12 h-16 rounded object-cover" alt="Cover">
              <span>{{ optional($ebook)->title }}</span>
            </div>
          </td>
          <td class="py-3 px-4">{{ optional(optional($ebook)->author)->name }}</td>
          <td class="text-center py-3 px-4">{{ $lib->purchased_at ? \Illuminate\Support\Carbon::parse($lib->purchased_at)->format('d M Y') : '-' }}</td>
          <td class="text-center py-3 px-4">
            @if(optional($ebook)->file_url)
              <a href="{{ $ebook->file_url }}" target="_blank" class="px-3 py-2 rounded bg-white/10 hover:bg-white/20">Unduh/Lihat</a>
            @else
              <span class="text-white/60">Tidak tersedia</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="py-8 px-4 text-center text-white/60">Belum ada e-book yang dibeli.</td>
        </tr>
      @endforelse
    </x-ui.table>
  </div>
</div>
@endsection

