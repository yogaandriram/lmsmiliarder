@extends('components.layout.admin')
@section('page_title', 'Tag')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-semibold">Kelola Tag</h2>
        <x-ui.btn-primary href="{{ route('admin.tags.create') }}">Tambah Tag</x-ui.btn-primary>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
  <x-ui.stat-card icon="ph ph-tag" label="Total Tag" value="{{ $tags->count() }}" />
</div>

<x-ui.table title="Daftar Tag">
  <x-slot:header>
    <tr>
      <th class="py-2">Nama</th>
      <th class="py-2">Slug</th>
      <th class="py-2">Aktif</th>
      <th class="py-2">Aksi</th>
    </tr>
  </x-slot:header>

  <x-slot:actions>
        <x-ui.btn-secondary href="{{ route('admin.tags.create') }}" size="sm">Tambah Tag</x-ui.btn-secondary>
  </x-slot:actions>

  @forelse($tags as $t)
    <tr class="border-t border-white/10">
      <td class="py-2">{{ $t->name }}</td>
      <td class="py-2">{{ $t->slug }}</td>
      <td class="py-2">
        <form method="POST" action="{{ route('admin.tags.toggle', $t) }}">
          @csrf @method('PATCH')
          <label class="flex items-center gap-2 select-none">
            <input type="checkbox" class="sr-only peer" {{ $t->is_active ? 'checked' : '' }} onchange="this.form.submit()">
            <div class="relative w-10 h-5 rounded-full bg-white/10 border border-white/20 transition-colors peer-checked:bg-amber-400/30 before:content-[''] before:absolute before:top-0.5 before:left-1 before:w-4 before:h-4 before:rounded-full before:bg-white/70 before:shadow-sm before:transition-transform peer-checked:before:translate-x-5"></div>
          </label>
        </form>
      </td>
      <td class="py-2">
        <a href="{{ route('admin.tags.edit', $t) }}" class="text-yellow-400">Edit</a>
        <form method="POST" action="{{ route('admin.tags.destroy', $t) }}" class="inline">
          @csrf @method('DELETE')
          <button class="ml-2 text-red-400" onclick="return confirm('Hapus tag?')">Hapus</button>
        </form>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="3" class="py-3 text-white/70">Belum ada tag.</td>
    </tr>
  @endforelse
</x-ui.table>
@endsection