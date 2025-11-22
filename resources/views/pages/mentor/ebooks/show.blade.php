@extends('components.layout.mentor')
@section('page_title', 'Detail E-book')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <img src="{{ $ebook->cover_image_url ?? 'https://placehold.co/120x160' }}" alt="{{ $ebook->title }}" class="w-20 h-28 rounded-lg object-cover">
            <div>
                <h2 class="text-2xl font-semibold">{{ $ebook->title }}</h2>
                <p class="text-white/70">E-book Digital</p>
            </div>
        </div>
        <div class="flex gap-3">
            <x-ui.btn-secondary href="{{ route('mentor.ebooks.index') }}" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
            <x-ui.btn-primary href="{{ route('mentor.ebooks.edit', $ebook) }}" icon="fa-solid fa-edit">Edit E-book</x-ui.btn-primary>
        </div>
    </div>

    <!-- Ebook Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-yellow-300">Status</div>
            <div class="text-lg font-bold">
                @if($ebook->status == 'published')
                    <span class="text-green-400">Aktif</span>
                @elseif($ebook->status == 'draft')
                    <span class="text-gray-400">Draft</span>
                @else
                    <span class="text-orange-400">Arsip</span>
                @endif
            </div>
        </div>
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-yellow-300">Total Penjualan</div>
            <div class="text-lg font-bold">0</div>
        </div>
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-yellow-300">Harga</div>
            <div class="text-lg font-bold">Rp {{ number_format($ebook->price, 0, ',', '.') }}</div>
        </div>
        <div class="glass p-4 rounded-lg">
            <div class="text-sm text-yellow-300">Dibuat</div>
            <div class="text-lg font-bold">{{ $ebook->created_at->format('d M Y') }}</div>
        </div>
    </div>

    <!-- Ebook Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Basic Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Deskripsi E-book</h3>
                <div class="prose prose-invert max-w-none">
                    {!! nl2br(e($ebook->description)) !!}
                </div>
            </div>

            <!-- File Information -->
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Informasi File</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                        <span class="text-white/90">URL File E-book</span>
                        <a href="{{ $ebook->file_url }}" target="_blank" class="text-yellow-400 hover:text-yellow-300 flex items-center gap-2">
                            <i class="fa-solid fa-external-link-alt"></i>
                            Lihat File
                        </a>
                    </div>
                    @if($ebook->cover_image_url)
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                            <span class="text-white/90">URL Cover Image</span>
                            <a href="{{ $ebook->cover_image_url }}" target="_blank" class="text-yellow-400 hover:text-yellow-300 flex items-center gap-2">
                                <i class="fa-solid fa-external-link-alt"></i>
                                Lihat Cover
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Cover Preview -->
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Preview Cover</h3>
                <img src="{{ $ebook->cover_image_url ?? 'https://placehold.co/240x320' }}" alt="{{ $ebook->title }}" class="w-full rounded-lg">
            </div>

            <!-- Quick Actions -->
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <x-ui.btn-secondary href="{{ $ebook->file_url }}" target="_blank" icon="fa-solid fa-download" class="w-full">Download File</x-ui.btn-secondary>
                    <x-ui.btn-secondary href="#" icon="fa-solid fa-chart-line" class="w-full">Lihat Analitik</x-ui.btn-secondary>
                    <x-ui.btn-secondary href="#" icon="fa-solid fa-share" class="w-full">Bagikan Link</x-ui.btn-secondary>
                </div>
            </div>

            <!-- Ebook Settings -->
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Pengaturan E-book</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-white/90">Status</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($ebook->status == 'published') bg-green-500/20 text-green-300
                            @elseif($ebook->status == 'draft') bg-gray-500/20 text-gray-300
                            @else bg-orange-500/20 text-orange-300 @endif">
                            {{ ucfirst($ebook->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/90">Dibuat</span>
                        <span class="text-white/70 text-sm">{{ $ebook->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/90">Diperbarui</span>
                        <span class="text-white/70 text-sm">{{ $ebook->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection