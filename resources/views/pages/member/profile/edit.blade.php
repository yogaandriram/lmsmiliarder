@extends('components.layout.member')
@section('page_title', 'Edit Profil')

@section('content')
<div class="space-y-8">
  <h2 class="text-2xl font-semibold">Edit Profil</h2>

  <div class="glass p-6 rounded-lg max-w-2xl">
    <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <x-ui.crud.input label="Nama" name="name" :value="$user->name" required variant="glass" />
      <x-ui.crud.input label="Email" name="email" :value="$user->email" variant="glass" disabled />
      <x-ui.crud.input label="Pekerjaan" name="job_title" :value="old('job_title', $user->job_title)" variant="glass" placeholder="mis. Digital Marketing" />
      <x-ui.crud.textarea label="Bio" name="bio" variant="glass">{{ old('bio', $user->bio) }}</x-ui.crud.textarea>

      <div class="space-y-2">
        <label class="block text-sm text-gray-300">Foto Profil (avatar)</label>
        <div class="flex items-center gap-4">
          <img src="{{ $user->avatar_url ?? 'https://placehold.co/64x64' }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover">
          <x-ui.crud.input name="avatar" type="file" variant="glass" accept="image/*" />
        </div>
      </div>

      <div class="flex gap-3">
        <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Simpan</x-ui.btn-primary>
        <x-ui.btn-secondary href="{{ route('member.profile') }}">Batal</x-ui.btn-secondary>
      </div>
    </form>
  </div>
</div>
@endsection

