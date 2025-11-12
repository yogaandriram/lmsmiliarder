@extends('components.layout.admin')
@section('page_title', 'Pengaturan')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-semibold">Pengaturan</h2>
        <x-ui.btn-secondary href="{{ route('admin.dashboard') }}">Kembali</x-ui.btn-secondary>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="glass p-6 rounded">
    <h3 class="text-xl font-semibold mb-2">Tema</h3>
    <p class="text-white/70 mb-3">Toggle tampilan terang/gelap menggunakan komponen yang sama dengan navbar.</p>
    <div class="flex items-center gap-3">
      <x-ui.navbar.theme-toggle desktop="true" />
      <span class="text-white/70 text-sm">Klik ikon untuk mengganti mode.</span>
    </div>
  </div>

  <div class="glass p-6 rounded">
    <h3 class="text-xl font-semibold mb-2">Akses Cepat</h3>
    <div class="flex flex-wrap gap-3">
        <x-ui.btn-primary href="{{ route('admin.users.index') }}" icon="fa-solid fa-user-group">Kelola User</x-ui.btn-primary>
        <x-ui.btn-primary href="{{ route('admin.announcements.index') }}" icon="fa-solid fa-bullhorn">Pengumuman</x-ui.btn-primary>
        <x-ui.btn-primary href="{{ route('admin.admin-bank-accounts.index') }}" icon="fa-solid fa-building-columns">Rekening Admin</x-ui.btn-primary>
    </div>
  </div>
</div>
@endsection