@extends('components.layout.admin')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Verifikasi Mentor (Pending)</h2>

<div class="glass p-6 rounded">
    <table class="w-full text-left">
        <thead>
            <tr>
                <th class="py-2">Nama</th>
                <th class="py-2">Email</th>
                <th class="py-2">Dokumen</th>
                <th class="py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($pendingUsers as $user)
            <tr class="border-t border-white/10">
                <td class="py-2">{{ $user->name }}</td>
                <td class="py-2">{{ $user->email }}</td>
                <td class="py-2"><a href="{{ route('admin.mentor_verifications.show', $user) }}" class="text-yellow-300">Lihat Dokumen</a></td>
                <td class="py-2">
                    <form method="POST" action="{{ route('admin.mentor_verifications.approve_user', $user) }}" class="inline">
                        @csrf
                        <x-ui.btn-primary type="submit" size="sm">Approve</x-ui.btn-primary>
                    </form>
                    <form method="POST" action="{{ route('admin.mentor_verifications.reject_user', $user) }}" class="inline ml-2">
                        @csrf
                        <button class="px-3 py-1 bg-red-500 text-white rounded">Reject</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="py-3 text-white/70">Tidak ada pengajuan pending.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection