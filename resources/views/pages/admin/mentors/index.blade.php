@extends('components.layout.admin')
@section('page_title', 'Kelola Mentor')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Kelola Mentor</h2>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-ui.stat-card label="Total Mentor" value="{{ $mentors->total() }}" icon="fa-solid fa-user-tie" />
    <x-ui.stat-card label="Aktif (email terverifikasi)" value="{{ $mentors->filter(fn($m)=>!is_null($m->email_verified_at))->count() }}" icon="fa-solid fa-envelope-open-text" />
    <x-ui.stat-card label="Belum Verifikasi Email" value="{{ $mentors->filter(fn($m)=>is_null($m->email_verified_at))->count() }}" icon="fa-solid fa-envelope" />
  </div>

  <x-ui.table>
    <x-slot name="header">
      <tr>
        <th class="text-left py-3 px-4">Mentor</th>
        <th class="text-left py-3 px-4">Email</th>
        <th class="text-left py-3 px-4">Pekerjaan</th>
        <th class="text-center py-3 px-4">Dibuat</th>
        <th class="text-right py-3 px-4">Aksi</th>
      </tr>
    </x-slot>
    @forelse($mentors as $mentor)
    <tr class="border-b border-white/10 hover:bg-white/5">
      <td class="py-3 px-4">
        <div class="font-medium">{{ $mentor->name }}</div>
      </td>
      <td class="py-3 px-4">{{ $mentor->email }}</td>
      <td class="py-3 px-4">{{ $mentor->job_title ?? '-' }}</td>
      <td class="text-center py-3 px-4">{{ optional($mentor->created_at)->format('d M Y') }}</td>
      <td class="text-right py-3 px-4">
        <div class="flex gap-2 justify-end">
          <x-ui.btn-secondary href="{{ route('admin.users.show', $mentor) }}" size="sm" icon="fa-solid fa-eye">Detail</x-ui.btn-secondary>
          <x-ui.btn-secondary href="{{ route('admin.users.edit', $mentor) }}" size="sm" icon="fa-solid fa-edit">Edit</x-ui.btn-secondary>
        </div>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="5" class="py-8 px-4 text-center text-white/60">
        <i class="fa-solid fa-user-tie text-4xl mb-4"></i>
        <div class="text-lg font-medium mb-2">Belum ada mentor</div>
        <div class="text-sm text-white/70">Konversi user menjadi mentor melalui halaman Verifikasi Mentor</div>
      </td>
    </tr>
    @endforelse
  </x-ui.table>

  @if($mentors->hasPages())
    <div class="p-4">
      {{ $mentors->links() }}
    </div>
  @endif
</div>
@endsection

