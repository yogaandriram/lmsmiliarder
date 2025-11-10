@extends('components.layout.admin')
@section('page_title', 'Tag')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-semibold">Tambah Tag</h2>
  <x-ui.button.secondary href="{{ route('admin.tags.index') }}">Kembali</x-ui.button.secondary>
  </div>

<div class="glass p-6 rounded max-w-2xl">
  <form method="POST" action="{{ route('admin.tags.store') }}">
    @csrf
    <div class="mb-3">
      <x-form.input variant="glass" label="Nama" name="name" type="text" value="{{ old('name') }}" required />
    </div>
    <div class="mb-4">
      <label class="flex items-center gap-3 select-none">
        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
        <div class="relative w-12 h-6 rounded-full bg-white/10 border border-white/20 transition-colors peer-checked:bg-amber-400/30 before:content-[''] before:absolute before:top-0.5 before:left-1 before:w-5 before:h-5 before:rounded-full before:bg-white/70 before:shadow-sm before:transition-transform peer-checked:before:translate-x-6"></div>
        <span class="text-sm text-white/80">Aktif</span>
      </label>
    </div>
    <x-ui.button.primary type="submit">Simpan</x-ui.button.primary>
    <x-ui.button.secondary href="{{ route('admin.tags.index') }}" class="ml-2">Batal</x-ui.button.secondary>
  </form>
</div>
@endsection