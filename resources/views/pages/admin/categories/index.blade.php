@extends('components.layout.admin')
@section('page_title', 'Kategori')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold">Kelola Kategori</h2>
        <x-ui.btn-primary href="{{ route('admin.categories.create') }}">Tambah Kategori</x-ui.btn-primary>
</div>

@php
    $categoryCount = $categories->count();
    $latestCreated = optional($categories->sortByDesc('created_at')->first())->created_at;
    $latestCreatedText = $latestCreated ? $latestCreated->format('d M Y') : '-';
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <x-ui.stat-card label="Total Kategori" :value="$categoryCount" icon="fa-solid fa-folder" />
    <x-ui.stat-card label="Terakhir Dibuat" :value="$latestCreatedText" icon="fa-solid fa-clock" />
</div>

<x-ui.table title="Daftar Kategori">
    <x-slot:header>
        <tr>
            <th class="py-2">Nama</th>
            <th class="py-2">Slug</th>
            <th class="py-2">Aktif</th>
            <th class="py-2">Aksi</th>
        </tr>
    </x-slot:header>

    @forelse($categories as $c)
        <tr class="border-t border-white/10">
            <td class="py-2">{{ $c->name }}</td>
            <td class="py-2">{{ $c->slug }}</td>
            <td class="py-2">
                <form method="POST" action="{{ route('admin.categories.toggle', $c) }}">
                    @csrf @method('PATCH')
                    <label class="flex items-center gap-2 select-none">
                        <input type="checkbox" class="sr-only peer" {{ $c->is_active ? 'checked' : '' }} onchange="this.form.submit()">
                        <div class="relative w-10 h-5 rounded-full bg-white/10 border border-white/20 transition-colors peer-checked:bg-amber-400/30 before:content-[''] before:absolute before:top-0.5 before:left-1 before:w-4 before:h-4 before:rounded-full before:bg-white/70 before:shadow-sm before:transition-transform peer-checked:before:translate-x-5"></div>
                    </label>
                </form>
            </td>
            <td class="py-2">
                <a href="{{ route('admin.categories.edit', $c) }}" class="text-yellow-400">Edit</a>
                <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="inline">
                    @csrf @method('DELETE')
                    <button class="ml-2 text-red-400" onclick="return confirm('Hapus kategori?')">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="3" class="py-3 text-white/70">Belum ada kategori.</td></tr>
    @endforelse
</x-ui.table>
@endsection