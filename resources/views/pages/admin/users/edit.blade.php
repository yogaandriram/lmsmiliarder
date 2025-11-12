@extends('components.layout.admin')
@section('page_title', 'Edit User')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-semibold">Edit User</h2>
        <x-ui.btn-secondary href="{{ route('admin.users.index') }}">Kembali</x-ui.btn-secondary>
  </div>

<div class="glass p-6 rounded max-w-2xl">
  <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
      <x-ui.crud.input variant="glass" label="Nama" name="name" type="text" value="{{ old('name', $user->name) }}" required />
    </div>
    <div class="mb-3">
      <x-ui.crud.input variant="glass" label="Email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
    </div>
    <div class="mb-3">
      <x-ui.crud.input variant="glass" label="Password (isi jika ingin ganti)" name="password" type="password" showToggle="true" />
    </div>
    <div class="mb-3">
      <x-ui.crud.dropdown variant="glass" label="Role" name="role" :roles="$roles" :selected="old('role', $user->role)" required />
    </div>
    <div class="mb-3">
      <x-ui.crud.input variant="glass" label="Avatar (maks 200x200, ≤300KB)" name="avatar" type="file" accept="image/*" />
      <p class="text-xs text-white/60 mt-1">Saat ini:</p>
      <img src="{{ $user->avatar_url ?? 'https://placehold.co/80x80' }}" alt="avatar" class="mt-1 w-20 h-20 rounded-full object-cover border border-white/20">
    </div>
    <div class="mb-4">
      <x-ui.crud.textarea variant="glass" label="Bio" name="bio">{{ old('bio', $user->bio) }}</x-ui.crud.textarea>
    </div>

        <x-ui.btn-primary type="submit">Simpan Perubahan</x-ui.btn-primary>
        <x-ui.btn-secondary href="{{ route('admin.users.index') }}" class="ml-2">Batal</x-ui.btn-secondary>
  </form>
</div>
@endsection