@extends('components.layout.admin')
@section('page_title', 'Tambah User')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-semibold">Tambah User</h2>
        <x-ui.btn-secondary href="{{ route('admin.users.index') }}">Kembali</x-ui.btn-secondary>
  </div>

<div class="glass p-6 rounded max-w-2xl">
  <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <x-ui.crud.input variant="glass" label="Nama" name="name" type="text" value="{{ old('name') }}" required />
    </div>
    <div class="mb-3">
      <x-ui.crud.input variant="glass" label="Email" name="email" type="email" value="{{ old('email') }}" required />
    </div>
    <div class="mb-3">
      <x-ui.crud.input variant="glass" label="Password" name="password" type="password" required showToggle="true" />
    </div>
    <div class="mb-3">
      <x-ui.crud.dropdown variant="glass" label="Role" name="role" :roles="$roles" :selected="old('role')" required />
    </div>
    <div class="mb-3">
      <x-ui.crud.input variant="glass" label="Avatar (maks 200x200, ≤300KB)" name="avatar" type="file" accept="image/*" />
    </div>
    <div class="mb-4">
      <x-ui.crud.textarea variant="glass" label="Bio" name="bio">{{ old('bio') }}</x-ui.crud.textarea>
    </div>

        <x-ui.btn-primary type="submit">Simpan</x-ui.btn-primary>
        <x-ui.btn-secondary href="{{ route('admin.users.index') }}" class="ml-2">Batal</x-ui.btn-secondary>
  </form>
</div>
@endsection