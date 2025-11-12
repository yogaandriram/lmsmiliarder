@extends('components.layout.admin')
@section('page_title', 'Kelola User')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-semibold">Kelola User</h2>
        <x-ui.btn-primary href="{{ route('admin.users.create') }}">Tambah User</x-ui.btn-primary>
  </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
  <x-ui.stat-card icon="fa-solid fa-user-group" label="Total User" :value="$users->total()" />
  <x-ui.stat-card icon="fa-solid fa-user-shield" label="Admin" :value="\App\Models\User::where('role','admin')->count()" />
  <x-ui.stat-card icon="fa-solid fa-chalkboard-user" label="Mentor" :value="\App\Models\User::where('role','mentor')->count()" />
</div>

<x-ui.table title="Daftar User">
  <x-slot:header>
    <tr>
      <th class="py-2">Nama</th>
      <th class="py-2">Email</th>
      <th class="py-2">Role</th>
      <th class="py-2">Aksi</th>
    </tr>
  </x-slot:header>

  @forelse($users as $u)
    <tr class="border-t border-white/10">
      <td class="py-2">{{ $u->name }}</td>
      <td class="py-2">{{ $u->email }}</td>
      <td class="py-2"><span class="uppercase">{{ $u->role }}</span></td>
      <td class="py-2">
        <a href="{{ route('admin.users.show', $u) }}" class="text-blue-300">Lihat</a>
        <a href="{{ route('admin.users.edit', $u) }}" class="ml-2 text-yellow-300">Edit</a>
        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline">
          @csrf @method('DELETE')
          <button class="ml-2 text-red-400" onclick="return confirm('Hapus user ini?')">Hapus</button>
        </form>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="4" class="py-3 text-white/70">Belum ada user.</td>
    </tr>
  @endforelse
</x-ui.table>

<div class="mt-6">
  {{ $users->links() }}
  </div>
@endsection