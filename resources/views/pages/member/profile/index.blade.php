@extends('components.layout.member')
@section('page_title', 'Profil Saya')

@section('content')
@php($user = auth()->user())
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-semibold">Profil Saya</h2>
  <div class="flex gap-2">
        <x-ui.btn-secondary href="{{ route('member.dashboard') }}">Kembali</x-ui.btn-secondary>
        <x-ui.btn-primary href="{{ route('member.settings') }}">Pengaturan</x-ui.btn-primary>
        <x-ui.btn-primary href="{{ route('member.profile.edit') }}" icon="fa-solid fa-pen">Edit Profil</x-ui.btn-primary>
  </div>
  </div>

<div class="glass p-6 rounded max-w-2xl space-y-3">
  <div class="flex items-center gap-4">
    <img src="{{ $user->avatar_url ?? 'https://placehold.co/80x80' }}" alt="avatar" class="w-20 h-20 rounded-full object-cover border border-white/20">
    <div>
      <h3 class="text-xl font-semibold">{{ $user->name }}</h3>
      <p class="text-white/70">{{ $user->email }}</p>
      <span class="inline-block mt-1 px-3 py-1 rounded bg-white/10 border border-white/20 text-xs uppercase">{{ $user->role }}</span>
    </div>
  </div>
  <div>
    <h4 class="font-semibold mb-1">Bio</h4>
    <p class="text-white/80">{{ $user->bio ?: '—' }}</p>
  </div>
</div>
@endsection

