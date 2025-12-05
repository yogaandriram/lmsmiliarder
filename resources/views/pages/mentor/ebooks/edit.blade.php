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
        <form method="POST" action="{{ route('mentor.ebooks.update', $ebook) }}" class="space-y-6" enctype="multipart/form-data">
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
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="cover" class="block text-sm font-medium text-white/90 mb-2">Upload Cover Image</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ $ebook->cover_image_url ?? 'https://placehold.co/120x80' }}" class="w-20 h-14 rounded object-cover" alt="Current Cover">
                            <x-ui.crud.input name="cover" id="cover" type="file" accept="image/*" variant="glass" />
                        </div>
                        @error('cover')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-white/60 text-sm mt-1">Unggah gambar cover baru (opsional).</p>
                    </div>
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-white/90 mb-2">Upload File E-book</label>
                    <x-ui.crud.input name="file" id="file" type="file" variant="glass" />
                    @error('file')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-white/60 text-sm mt-1">Unggah file e-book (PDF, EPUB, dll.). Biarkan kosong jika tidak mengubah.</p>
                </div>
            </div>

            <!-- Bagi Hasil -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Bagi Hasil</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-white/90 mb-2 flex items-center gap-2">
                            Untuk Mentor (%)
                            <span class="relative group inline-flex items-center">
                                <i class="fa-solid fa-circle-info text-white/70"></i>
                                <span class="absolute left-1/2 -translate-x-1/2 mt-6 hidden group-hover:block whitespace-nowrap text-xs bg-black/80 text-white px-3 py-2 rounded shadow z-50">
                                    Nilai 0..100. Field Admin menyesuaikan agar total selalu 100.
                                </span>
                            </span>
                        </label>
                        <input type="number" min="0" max="100" name="mentor_share_percent" id="mentor_share_percent_ebook_edit"
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white"
                               value="{{ old('mentor_share_percent', $ebook->mentor_share_percent) }}" required />
                        @error('mentor_share_percent')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-white/90 mb-2">Untuk Admin (%)</label>
                        <input type="number" min="0" max="100" id="admin_share_percent_ebook_edit"
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white" value="{{ 100 - (int)($ebook->mentor_share_percent ?? 80) }}" readonly />
                    </div>
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
<script>
(function(){
  var mentor = document.getElementById('mentor_share_percent_ebook_edit');
  var admin = document.getElementById('admin_share_percent_ebook_edit');
  if(mentor && admin){
    var clamp = function(n){ n = parseInt(n,10); if(isNaN(n)) n = 0; return Math.min(100, Math.max(0, n)); };
    var sync = function(){ var m = clamp(mentor.value); mentor.value = m; admin.value = 100 - m; };
    mentor.addEventListener('input', sync);
    sync();
  }
})();
</script>
@endsection
