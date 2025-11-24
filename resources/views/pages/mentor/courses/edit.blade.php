@extends('components.layout.mentor')
@section('page_title', 'Edit Kursus')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold">Edit Kursus</h2>
            <p class="text-white/70 mt-1">Perbarui informasi kursus Anda</p>
        </div>
        <x-ui.btn-secondary href="{{ route('mentor.courses.index') }}" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
    </div>

    <!-- Form -->
    <div class="glass p-6 rounded-lg">
        <form method="POST" action="{{ route('mentor.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Informasi Dasar</h3>
                
                <x-ui.crud.input label="Judul Kursus *" name="title" :value="old('title', $course->title)" required variant="glass" />

                <x-ui.crud.input label="Slug Kursus" name="slug" id="slug_edit_input" :value="old('slug', $course->slug)" variant="glass" placeholder="contoh: nama-kursus" />

                <x-ui.crud.textarea label="Deskripsi *" name="description" variant="glass">{{ old('description', $course->description) }}</x-ui.crud.textarea>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-white/90 mb-2">Kategori *</label>
                        <select name="category_id" id="category_id" required
                                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white">
                            <option value="" class="bg-gray-800">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }} 
                                        class="bg-gray-800">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-white/90 mb-2 flex items-center gap-2">
                            Harga (Rp) *
                            <span class="relative group inline-flex items-center">
                                <i class="fa-solid fa-circle-info text-white/70"></i>
                                <span class="absolute left-1/2 -translate-x-1/2 mt-6 hidden group-hover:block whitespace-nowrap text-xs bg-black/80 text-white px-3 py-2 rounded shadow z-50">
                                    Masukkan 0 untuk kursus gratis. Angka diformat ribuan otomatis.
                                </span>
                            </span>
                        </label>
                        <input id="price_display_edit" type="text" inputmode="numeric" autocomplete="off"
                               class="w-full p-2 rounded bg-white/10 border border-white/20 text-white"
                               value="{{ number_format((int)old('price', $course->price), 0, ',', '.') }}"
                               placeholder="0" />
                        <input type="hidden" name="price" id="price_value_edit" value="{{ (int)old('price', $course->price) }}" />
                        @error('price')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-ui.crud.input label="URL Video Perkenalan" name="intro_video_url" type="url" :value="old('intro_video_url', $course->intro_video_url)" variant="glass" placeholder="https://youtube.com/..." />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/90 mb-2">Thumbnail (max 2 MB)</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/120x80' }}" class="w-20 h-14 rounded object-cover" alt="Current Thumbnail">
                            <x-ui.crud.input name="thumbnail" type="file" accept="image/*" variant="glass" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Tag Kursus</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($tags as $tag)
                        <label class="flex items-center gap-2 p-3 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                   {{ in_array($tag->id, old('tags', $course->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                   class="rounded border-white/30 bg-white/10 text-yellow-400 focus:ring-yellow-400">
                            <span class="text-white/90">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Status Publikasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center gap-3 p-4 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                        <input type="radio" name="status" value="draft" 
                               {{ old('status', $course->status) == 'draft' ? 'checked' : '' }}
                               class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <div class="font-medium">Draft</div>
                            <div class="text-sm text-white/70">Kursus belum dipublikasikan</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                        <input type="radio" name="status" value="published" 
                               {{ old('status', $course->status) == 'published' ? 'checked' : '' }}
                               class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <div class="font-medium">Publikasikan</div>
                            <div class="text-sm text-white/70">Kursus dapat diakses siswa</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                        <input type="radio" name="status" value="archived" 
                               {{ old('status', $course->status) == 'archived' ? 'checked' : '' }}
                               class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <div class="font-medium">Arsipkan</div>
                            <div class="text-sm text-white/70">Kursus disembunyikan</div>
                        </div>
                    </label>
                </div>
                @error('status')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
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
                        <input type="number" min="0" max="100" name="mentor_share_percent" id="mentor_share_percent_edit"
                               class="w-full p-2 rounded bg-white/10 border border-white/20 text-white"
                               value="{{ (int)old('mentor_share_percent', (int)($course->mentor_share_percent ?? 80)) }}" required />
                        @error('mentor_share_percent')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-white/90 mb-2">Untuk Admin (%)</label>
                        <input type="number" min="0" max="100" id="admin_share_percent_edit"
                               class="w-full p-2 rounded bg-white/10 border border-white/20 text-white" value="{{ 100 - (int)old('mentor_share_percent', (int)($course->mentor_share_percent ?? 80)) }}" readonly />
                    </div>
                </div>
            </div>

            <!-- Diskusi Kursus -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Diskusi Kursus</h3>
                <label class="flex items-center gap-3 p-3 bg-white/5 rounded">
                    <input type="checkbox" name="enable_discussion" value="1" class="w-5 h-5 rounded border-white/30 bg-white/10" {{ $course->discussionGroup ? 'checked' : '' }}>
                    <span class="text-white/90">Aktifkan grup diskusi untuk kursus ini</span>
                </label>
                <p class="text-sm text-white/60">Satu kursus memiliki satu grup diskusi yang berisi mentor, admin, dan semua siswa terdaftar.</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-6">
                <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Perbarui Kursus</x-ui.btn-primary>
                <x-ui.btn-secondary href="{{ route('mentor.courses.index') }}" icon="fa-solid fa-times">Batal</x-ui.btn-secondary>
            </div>
        </form>
    </div>
</div>
<script>
(function(){
  var disp = document.getElementById('price_display_edit');
  var hidden = document.getElementById('price_value_edit');
  var mentor = document.getElementById('mentor_share_percent_edit');
  var admin = document.getElementById('admin_share_percent_edit');
  if(disp && hidden){
    var format = function(v){
      v = (v||'').toString().replace(/[^0-9]/g,'');
      if(v === ''){ hidden.value = ''; return ''; }
      hidden.value = parseInt(v,10) || 0;
      return v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };
    disp.addEventListener('input', function(){
      var pos = disp.selectionStart;
      var before = disp.value;
      disp.value = format(disp.value);
      if(document.activeElement === disp){
        var diff = disp.value.length - before.length;
        disp.setSelectionRange(Math.max(0, pos+diff), Math.max(0, pos+diff));
      }
    });
    disp.value = format(disp.value);
  }
  if(mentor && admin){
    var clamp = function(n){ n = parseInt(n,10); if(isNaN(n)) n = 0; return Math.min(100, Math.max(0, n)); };
    var sync = function(){ var m = clamp(mentor.value); mentor.value = m; admin.value = 100 - m; };
    mentor.addEventListener('input', sync);
    sync();
  }
  var slugInput = document.getElementById('slug_edit_input');
  if(slugInput){
    var slugify = function(v){ v = (v||'').toString().toLowerCase(); v = v.replace(/[^a-z0-9\s-]/g,''); v = v.trim().replace(/\s+/g,'-').replace(/-+/g,'-'); return v; };
    slugInput.addEventListener('input', function(){ slugInput.value = slugify(slugInput.value); });
  }
})();
</script>
@endsection