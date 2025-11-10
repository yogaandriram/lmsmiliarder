@extends('components.layout.admin')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Pengumuman</h2>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="glass p-6 rounded">
        <h3 class="text-xl mb-4">Tambah Pengumuman</h3>
        <form method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf
            <div class="mb-3">
    <x-form.input variant="glass" label="Judul" name="title" type="text" value="{{ old('title') }}" required />
            </div>
            <div class="mb-3">
    <x-form.textarea variant="glass" label="Konten" name="content" rows="5">{{ old('content') }}</x-form.textarea>
            </div>
            <x-ui.button.primary type="submit">Simpan</x-ui.button.primary>
        </form>
    </div>

    <div class="glass p-6 rounded">
        <h3 class="text-xl mb-4">Daftar Pengumuman</h3>
        <ul class="space-y-3">
            @forelse($announcements as $a)
                <li class="border border-white/10 rounded p-3">
                    <div class="font-semibold">{{ $a->title }}</div>
                    <div class="text-white/80">{{ $a->content }}</div>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}" class="mt-2">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1 bg-red-500 text-white rounded" onclick="return confirm('Hapus pengumuman?')">Hapus</button>
                    </form>
                </li>
            @empty
                <li class="text-white/70">Belum ada pengumuman.</li>
            @endforelse
        </ul>
    </div>
@endsection