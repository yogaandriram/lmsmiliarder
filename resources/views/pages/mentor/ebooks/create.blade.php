@extends('components.layout.mentor')
@section('page_title', 'Buat E-book Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold">Buat E-book Baru</h2>
            <p class="text-white/70 mt-1">Buat e-book digital untuk dibagikan kepada pembaca</p>
        </div>
        <x-ui.btn-secondary href="{{ route('mentor.ebooks.index') }}" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
    </div>

    <!-- Form -->
    <div class="glass p-6 rounded-lg">
        <form method="POST" action="{{ route('mentor.ebooks.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Informasi Dasar</h3>
                
                <div>
                    <label for="title" class="block text-sm font-medium text-white/90 mb-2">Judul E-book *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/50">
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-white/90 mb-2">Deskripsi *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/50">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-white/90 mb-2">Harga (Rp) *</label>
                        <input type="number" name="price" id="price" value="{{ old('price', 0) }}" required min="0"
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/50">
                        @error('price')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cover" class="block text-sm font-medium text-white/90 mb-2">Upload Cover Image</label>
                        <x-ui.crud.input name="cover" id="cover" type="file" accept="image/*" variant="glass" />
                        @error('cover')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-white/90 mb-2">Upload File E-book *</label>
                    <x-ui.crud.input name="file" id="file" type="file" required variant="glass" />
                    @error('file')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-white/60 text-sm mt-1">Unggah file e-book (PDF, EPUB, dll.).</p>
                </div>
                <div class="glass p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-yellow-400">Preview E-book</h3>
                        <div class="text-xs text-white/60">Geser halaman seperti buku, lengkap dengan suara</div>
                    </div>
                    <div id="ebook_preview_wrapper_create" class="relative w-full">
                        <div id="ebook_flipbook_create" class="w-full h-[60vh] bg-black/30 rounded overflow-hidden"></div>
                    </div>
                    <div id="ebook_preview_note_create" class="mt-3 text-white/60 text-sm hidden"></div>
                </div>
            </div>

            <!-- Status -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Status Publikasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center gap-3 p-4 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                        <input type="radio" name="status" value="draft" 
                               {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}
                               class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <div class="font-medium">Draft</div>
                            <div class="text-sm text-white/70">E-book belum dipublikasikan</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                        <input type="radio" name="status" value="published" 
                               {{ old('status') == 'published' ? 'checked' : '' }}
                               class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <div class="font-medium">Publikasikan</div>
                            <div class="text-sm text-white/70">E-book dapat diakses pembaca</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
                        <input type="radio" name="status" value="archived" 
                               {{ old('status') == 'archived' ? 'checked' : '' }}
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
                <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Simpan E-book</x-ui.btn-primary>
                <x-ui.btn-secondary href="{{ route('mentor.ebooks.index') }}" icon="fa-solid fa-times">Batal</x-ui.btn-secondary>
            </div>
        </form>
    </div>
</div>
<script>
(function(){
  var fileInput = document.getElementById('file');
  var flipEl = document.getElementById('ebook_flipbook_create');
  var noteEl = document.getElementById('ebook_preview_note_create');
  var pdfLoaded = false, flipLoaded = false;
  function loadScript(src, cb){ var s=document.createElement('script'); s.src=src; s.onload=cb; document.head.appendChild(s); }
  function ensureDeps(cb){
    var need = 0, done = 0; function inc(){ done++; if(done === need) cb(); }
    if(!pdfLoaded){ need++; loadScript('https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js', function(){ pdfLoaded = true; window['pdfjsLib'].GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js'; inc(); }); }
    if(!flipLoaded){ need++; var css=document.createElement('link'); css.rel='stylesheet'; css.href='https://cdn.jsdelivr.net/npm/st-pageflip@1.2.6/dist/css/st-pageflip.min.css'; document.head.appendChild(css); loadScript('https://cdn.jsdelivr.net/npm/st-pageflip@1.2.6/dist/js/page-flip.min.js', function(){ flipLoaded = true; inc(); }); }
    if(need===0) cb();
  }
  function playPaperSound(){ try{ var ctx=new (window.AudioContext||window.webkitAudioContext)(); var duration=0.2; var buffer=ctx.createBuffer(1, ctx.sampleRate*duration, ctx.sampleRate); var data=buffer.getChannelData(0); for(var i=0;i<data.length;i++){ data[i]=(Math.random()*2-1)*(1-i/data.length)*0.25; } var src=ctx.createBufferSource(); src.buffer=buffer; var f=ctx.createBiquadFilter(); f.type='lowpass'; f.frequency.value=1200; src.connect(f); f.connect(ctx.destination); src.start(); }catch(e){} }
  function renderPdfToFlip(arrayBuffer){ ensureDeps(function(){ window['pdfjsLib'].getDocument({ data: arrayBuffer }).promise.then(function(pdf){ var images=[], tasks=[]; var maxPages=Math.min(pdf.numPages, 30); for(var p=1;p<=maxPages;p++){ tasks.push(pdf.getPage(p).then(function(page){ var viewport=page.getViewport({ scale: 1.5 }); var canvas=document.createElement('canvas'); var ctx=canvas.getContext('2d'); canvas.width=viewport.width; canvas.height=viewport.height; return page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function(){ images.push(canvas.toDataURL('image/jpeg', 0.9)); }); })); } Promise.all(tasks).then(function(){ images.sort(); var pf=new window['St'].PageFlip(flipEl, { width: 800, height: 1100, size: 'stretch', maxShadowOpacity: 0.5, usePortrait: true, showCover: true, mobileScrollSupport: true, flippingTime: 700 }); pf.loadFromImages(images); pf.on('flip', function(){ playPaperSound(); }); }); }); }); }
  function handleFile(){ var f = fileInput && fileInput.files && fileInput.files[0]; if(!f){ return; } var isPdf = /pdf$/i.test(f.type) || /\.pdf$/i.test(f.name); if(!isPdf){ if(noteEl){ noteEl.classList.remove('hidden'); noteEl.textContent='Preview tersedia untuk file PDF.'; } return; } if(noteEl){ noteEl.classList.add('hidden'); } var reader=new FileReader(); reader.onload=function(e){ renderPdfToFlip(e.target.result); }; reader.readAsArrayBuffer(f); }
  if(fileInput){ fileInput.addEventListener('change', handleFile); }
})();
</script>
@endsection