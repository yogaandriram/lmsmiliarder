@extends('components.layout.mentor')
@section('page_title', 'E-book Saya')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">E-book Saya</h2>
        <x-ui.btn-primary href="{{ route('mentor.ebooks.create') }}" icon="fa-solid fa-plus">Buat E-book Baru</x-ui.btn-primary>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-yellow-300">Total E-book</div>
            <div class="text-2xl font-bold">{{ $ebooks->total() }}</div>
        </div>
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-green-300">Dipublikasikan</div>
            <div class="text-2xl font-bold">{{ $ebooks->where('status', 'published')->count() }}</div>
        </div>
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-gray-300">Total Penjualan</div>
            <div class="text-2xl font-bold">0</div>
        </div>
    </div>

    <!-- Ebooks Table -->
    <div class="glass rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/5">
                    <tr>
                        <th class="text-left py-3 px-4">E-book</th>
                        <th class="text-left py-3 px-4">Deskripsi</th>
                        <th class="text-center py-3 px-4">Harga</th>
                        <th class="text-center py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4">Dibuat</th>
                        <th class="text-right py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ebooks as $ebook)
                    <tr class="border-b border-white/10 hover:bg-white/5">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $ebook->cover_image_url ?? 'https://placehold.co/60x80' }}" alt="{{ $ebook->title }}" class="w-12 h-16 rounded object-cover">
                                <div>
                                    <div class="font-medium">{{ $ebook->title }}</div>
                                    <div class="text-sm text-white/70">{{ Str::limit($ebook->description, 30) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-white/80 text-sm">{{ Str::limit($ebook->description, 50) }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-semibold">Rp {{ number_format($ebook->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            @if($ebook->status == 'published')
                                <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full">Aktif</span>
                            @elseif($ebook->status == 'draft')
                                <span class="px-2 py-1 bg-gray-500/20 text-gray-300 text-xs rounded-full">Draft</span>
                            @else
                                <span class="px-2 py-1 bg-orange-500/20 text-orange-300 text-xs rounded-full">Arsip</span>
                            @endif
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-white/70">{{ $ebook->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-right py-3 px-4">
                            <div class="flex gap-2 justify-end">
                                <x-ui.btn-secondary href="{{ route('mentor.ebooks.show', $ebook) }}" size="sm" icon="fa-solid fa-eye">Detail</x-ui.btn-secondary>
                                <x-ui.btn-secondary href="{{ route('mentor.ebooks.edit', $ebook) }}" size="sm" icon="fa-solid fa-edit">Edit</x-ui.btn-secondary>
                                <form method="POST" action="{{ route('mentor.ebooks.destroy', $ebook) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus e-book ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.btn-secondary type="submit" size="sm" icon="fa-solid fa-trash">Hapus</x-ui.btn-secondary>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 px-4 text-center text-white/60">
                            <i class="fa-solid fa-book-open text-4xl mb-4"></i>
                            <div class="text-lg font-medium mb-2">Belum ada e-book</div>
                            <div class="text-sm text-white/70 mb-4">Buat e-book pertama Anda untuk memulai menjual</div>
                            <x-ui.btn-primary href="{{ route('mentor.ebooks.create') }}" icon="fa-solid fa-plus">Buat E-book</x-ui.btn-primary>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($ebooks->hasPages())
        <div class="p-4 border-t border-white/10">
            {{ $ebooks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection