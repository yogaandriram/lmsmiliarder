@extends('components.layout.admin')
@section('page_title', 'Kategori')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Edit Kategori</h2>

<div class="glass p-6 rounded max-w-2xl">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <x-ui.crud.input variant="glass" label="Nama" name="name" type="text" value="{{ old('name', $category->name) }}" required />
        </div>
        <div class="mb-3">
            <x-ui.crud.textarea variant="glass" label="Deskripsi" name="description">{{ old('description', $category->description) }}</x-ui.crud.textarea>
        </div>
        <div class="mb-4">
            <label class="flex items-center gap-3 select-none">
                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <div class="relative w-12 h-6 rounded-full bg-white/10 border border-white/20 transition-colors peer-checked:bg-amber-400/30 before:content-[''] before:absolute before:top-0.5 before:left-1 before:w-5 before:h-5 before:rounded-full before:bg-white/70 before:shadow-sm before:transition-transform peer-checked:before:translate-x-6"></div>
                <span class="text-sm text-white/80">Aktif</span>
            </label>
        </div>
        <x-ui.btn-primary type="submit">Simpan</x-ui.btn-primary>
        <x-ui.btn-secondary href="{{ route('admin.categories.index') }}" class="ml-2">Batal</x-ui.btn-secondary>
    </form>
</div>
@endsection