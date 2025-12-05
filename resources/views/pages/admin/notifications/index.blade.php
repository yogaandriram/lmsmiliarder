@extends('components.layout.admin')
@section('page_title','Notifikasi')

@section('content')
<div class="space-y-6">
  <h2 class="text-2xl font-semibold">Notifikasi</h2>

  <div class="glass p-6 rounded">
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-2 px-3">Waktu</th>
          <th class="text-left py-2 px-3">Pesan</th>
          <th class="text-left py-2 px-3">Aksi</th>
        </tr>
      </x-slot>
      @forelse($items as $n)
        <tr class="border-b border-white/10">
          <td class="py-2 px-3">{{ optional($n->created_at)->format('d M Y H:i') }}</td>
          <td class="py-2 px-3">{{ $n->message }}</td>
          <td class="py-2 px-3">
            @if($n->link_url)
              <a href="{{ $n->link_url }}" class="px-3 py-2 rounded bg-white/10 text-white/80 hover:bg-white/20">Lihat</a>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="3" class="py-4 px-3 text-white/70">Belum ada notifikasi.</td>
        </tr>
      @endforelse
    </x-ui.table>

    <div class="mt-4">
      <x-ui.pagination :paginator="$items" />
    </div>
  </div>
</div>
@endsection

