@extends('components.layout.mentor')
@section('page_title', 'Kursus Saya')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Kursus Saya</h2>
        <x-ui.btn-primary href="{{ route('mentor.courses.create') }}" icon="fa-solid fa-plus">Buat Kursus Baru</x-ui.btn-primary>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-ui.stat-card label="Total Kursus" value="{{ $courses->total() }}" icon="fa-solid fa-chalkboard" />
        <x-ui.stat-card label="Dipublikasikan" value="{{ $courses->where('status','published')->count() }}" icon="fa-solid fa-bullhorn" />
        <x-ui.stat-card label="Siswa Terdaftar" value="{{ $courses->sum('enrollments_count') }}" icon="fa-solid fa-users" />
    </div>

    <x-ui.table>
        <x-slot name="header">
            <tr>
                <th class="text-left py-3 px-4">Kursus</th>
                <th class="text-left py-3 px-4">Kategori</th>
                <th class="text-center py-3 px-4">Harga</th>
                <th class="text-center py-3 px-4">Status</th>
                <th class="text-center py-3 px-4">Verifikasi</th>
                <th class="text-center py-3 px-4">Siswa</th>
                <th class="text-center py-3 px-4">Dibuat</th>
                <th class="text-right py-3 px-4">Aksi</th>
            </tr>
        </x-slot>
                    @forelse($courses as $course)
                    <tr class="border-b border-white/10 hover:bg-white/5">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/60x40' }}" alt="{{ $course->title }}" class="w-12 h-8 rounded object-cover">
                                <div>
                                    <div class="font-medium">{{ $course->title }}</div>
                                    <div class="text-sm text-white/70 truncate max-w-xs">{{ Str::limit($course->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-white/80">{{ $course->category->name ?? '-' }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            @if($course->price == 0)
                                <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full mr-2">Gratis</span>
                            @endif
                            <span class="font-semibold">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            @if($course->status == 'published')
                                <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full">Aktif</span>
                            @elseif($course->status == 'draft')
                                <span class="px-2 py-1 bg-gray-500/20 text-gray-300 text-xs rounded-full">Draft</span>
                            @else
                                <span class="px-2 py-1 bg-orange-500/20 text-orange-300 text-xs rounded-full">Arsip</span>
                            @endif
                        </td>
                        <td class="text-center py-3 px-4">
                            @switch($course->verification_status)
                                @case('approved')
                                    <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full">Disetujui</span>
                                    @break
                                @case('rejected')
                                    <span class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded-full">Ditolak</span>
                                    @break
                                @default
                                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-300 text-xs rounded-full">Menunggu</span>
                            @endswitch
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-semibold">{{ $course->enrollments_count }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-white/70">{{ $course->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-right py-3 px-4">
                            <div class="flex gap-2 justify-end">
                                <x-ui.btn-secondary href="{{ route('mentor.courses.show', $course) }}" size="sm" icon="fa-solid fa-eye">Detail</x-ui.btn-secondary>
                                <x-ui.btn-secondary href="{{ route('mentor.courses.edit', $course) }}" size="sm" icon="fa-solid fa-edit">Edit</x-ui.btn-secondary>
                                <form method="POST" action="{{ route('mentor.courses.destroy', $course) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.btn-secondary type="submit" size="sm" icon="fa-solid fa-trash">Hapus</x-ui.btn-secondary>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 px-4 text-center text-white/60">
                            <i class="fa-solid fa-chalkboard-teacher text-4xl mb-4"></i>
                            <div class="text-lg font-medium mb-2">Belum ada kursus</div>
                            <div class="text-sm text-white/70 mb-4">Buat kursus pertama Anda untuk memulai mengajar</div>
                            <x-ui.btn-primary href="{{ route('mentor.courses.create') }}" icon="fa-solid fa-plus">Buat Kursus</x-ui.btn-primary>
                        </td>
                    </tr>
                    @endforelse
        </x-ui.table>
        
        <!-- Pagination -->
    @if($courses->hasPages())
    <div class="p-4">
        {{ $courses->links() }}
    </div>
    @endif
</div>
@endsection