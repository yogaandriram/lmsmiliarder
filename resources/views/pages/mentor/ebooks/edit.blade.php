@extends('components.layout.mentor')
@section('page_title', 'Edit E-book')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold">Edit E-book</h2>
            <p class="text-white/70 mt-1">Perbarui informasi e-book Anda</p>
        </div>
        <x-ui.btn-secondary href="{{ route('mentor.ebooks.index') }}" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
    </div>

    <!-- Form -->
    <div class="glass p-6 rounded-lg">
        <form method="POST" action="{{ route('mentor.ebooks.update', $ebook) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Informasi Dasar</h3>
                
                <div>
                    <label for="title" class="block text-sm font-medium text-white/90 mb-2">Judul E-book *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $ebook->title) }}" required
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/50">
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-white/90 mb-2">Deskripsi *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/50">{{ old('description', $ebook->description) }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-white/90 mb-2">Harga (Rp) *</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $ebook->price) }}" required min="0"
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/50">
                        @error('price')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cover_image_url" class="block text-sm font-medium text-white/90 mb-2">URL Cover Image</label>
                        <input type="url" name="cover_image_url" id="cover_image_url" value="{{ old('cover_image_url', $ebook->cover_image_url) }}"
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/50"
                               placeholder="https://example.com/cover.jpg">
                        @error('cover_image_url')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-white/60 text-sm mt-1">Masukkan URL gambar cover untuk e-book Anda</p>
                    </div>
                </div>

                <div>
                    <label for="file_url" class="block text-sm font-medium text-white/90 mb-2">URL File E-book *</label>
                    <input type="url" name="file_url" id="file_url" value="{{ old('file_url', $ebook->file_url) }}" required
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/50"
                           placeholder="https://example.com/ebook.pdf">
                    @error('file_url')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-white/60 text-sm mt-1">Masukkan URL file e-book (PDF, EPUB, dll.)</p>
                </div>
            </div>

            <!-- Status -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Status Publikasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center gap-3 p-4 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                        <input type="radio" name="status" value="draft" 
                               {{ old('status', $ebook->status) == 'draft' ? 'checked' : '' }}
                               class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <div class="font-medium">Draft</div>
                            <div class="text-sm text-white/70">E-book belum dipublikasikan</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                        <input type="radio" name="status" value="published" 
                               {{ old('status', $ebook->status) == 'published' ? 'checked' : '' }}
                               class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <div class="font-medium">Publikasikan</div>
                            <div class="text-sm text-white/70">E-book dapat diakses pembaca</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                        <input type="radio" name="status" value="archived" 
                               {{ old('status', $ebook->status) == 'archived' ? 'checked' : '' }}
                               class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <div class="font-medium">Arsipkan</div>
                            <div class="text-sm text-white/70">E-book disembunyikan</div>
                        </div>
                    </label>
                </div>
                @error('status')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-6">
                <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Perbarui E-book</x-ui.btn-primary>
                <x-ui.btn-secondary href="{{ route('mentor.ebooks.index') }}" icon="fa-solid fa-times">Batal</x-ui.btn-secondary>
            </div>
        </form>
    </div>
</div>
@endsection