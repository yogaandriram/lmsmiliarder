@extends('components.layout.mentor')

@section('content')
<div class="space-y-6">
  <h2 class="text-2xl font-semibold">Pengaturan</h2>
  <div class="glass p-6 rounded-lg">Pengaturan mentor belum tersedia.</div>
  <x-ui.btn-secondary href="{{ route('mentor.dashboard') }}">Kembali ke Dashboard</x-ui.btn-secondary>
  </div>
@endsection