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
                        <input id="price_display_ebook_edit" type="text" inputmode="numeric" autocomplete="off"
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white"
                               value="{{ number_format((int)old('price', (int)$ebook->price), 0, ',', '.') }}" placeholder="0">
                        <input type="hidden" name="price" id="price_value_ebook_edit" value="{{ (int)old('price', (int)$ebook->price) }}" />
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
                    <div class="text-white/70 text-sm mt-2">File sebelumnya: 
                        @if($ebook->file_url)
                            <a href="{{ $ebook->file_url }}" target="_blank" class="underline">{{ basename($ebook->file_url) }}</a>
                        @else
                            <span class="text-white/50">Belum ada</span>
                        @endif
                    </div>
                </div>
                <div class="glass p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-yellow-400">Preview E-book</h3>
                        <div class="text-xs text-white/60">Geser halaman seperti buku, lengkap dengan suara</div>
                    </div>
                    <div id="ebook_preview_wrapper_edit" class="relative w-full overflow-hidden">
                        <div id="ebook_flipbook_edit" data-file-url="{{ \Illuminate\Support\Str::startsWith($ebook->file_url ?? '', ['http://','https://']) ? ($ebook->file_url ?? '') : (($ebook->file_url ?? '') ? asset($ebook->file_url) : '') }}" class="w-full h-[70vh] bg-transparent rounded overflow-hidden"></div>
                        <div id="ebook_cover_overlay_edit" class="absolute inset-0 grid place-items-center bg-transparent z-10 hidden" style="pointer-events:none">
                            <img id="ebook_cover_image_edit" data-cover-url="{{ $ebook->cover_image_url }}" src="" alt="Cover" class="max-h-[68vh] rounded shadow-xl" />
                        </div>
                    </div>
                    <style>
                      #ebook_flipbook_edit img{ image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges; }
                    </style>
                    <div id="ebook_preview_note_edit" class="mt-3 text-white/60 text-sm hidden"></div>
                    <div class="mt-3 flex items-center gap-3">
                        <x-ui.btn-secondary id="ebook_prev_edit" icon="fa-solid fa-chevron-left">Prev</x-ui.btn-secondary>
                        <span id="ebook_page_info_edit" class="text-sm text-white/70">...</span>
                        <x-ui.btn-secondary id="ebook_next_edit" icon="fa-solid fa-chevron-right">Next</x-ui.btn-secondary>
                        <div class="ml-4 flex items-center gap-2">
                            <x-ui.btn-secondary id="ebook_zoom_out_edit" icon="fa-solid fa-magnifying-glass-minus"/>
                            <span id="ebook_zoom_info_edit" class="text-xs text-white/60">100%</span>
                            <x-ui.btn-secondary id="ebook_zoom_in_edit" icon="fa-solid fa-magnifying-glass-plus"/>
                            <x-ui.btn-secondary id="ebook_sound_edit" icon="fa-solid fa-volume-high"/>
                        </div>
                    </div>
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
  var disp = document.getElementById('price_display_ebook_edit');
  var hidden = document.getElementById('price_value_ebook_edit');
  if(mentor && admin){
    var clamp = function(n){ n = parseInt(n,10); if(isNaN(n)) n = 0; return Math.min(100, Math.max(0, n)); };
    var sync = function(){ var m = clamp(mentor.value); mentor.value = m; admin.value = 100 - m; };
    mentor.addEventListener('input', sync);
    sync();
  }
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
})();
</script>
<script>
(function(){
  var flipEl = document.getElementById('ebook_flipbook_edit');
  var fileInput = document.getElementById('file');
  var noteEl = document.getElementById('ebook_preview_note_edit');
  var coverOverlay = document.getElementById('ebook_cover_overlay_edit');
  var coverImg = document.getElementById('ebook_cover_image_edit');
  var prevBtn = document.getElementById('ebook_prev_edit'); var nextBtn = document.getElementById('ebook_next_edit');
  var zin = document.getElementById('ebook_zoom_in_edit'); var zout = document.getElementById('ebook_zoom_out_edit'); var soundBtn = document.getElementById('ebook_sound_edit');
  var zoomInfo = document.getElementById('ebook_zoom_info_edit'); var pageInfo = document.getElementById('ebook_page_info_edit');

  function isPdf(url){ return /\.pdf($|\?)/i.test(url || ''); }
  function playPaperSound(){ try{ var ctx=new (window.AudioContext||window.webkitAudioContext)(); var duration=0.25; var buffer=ctx.createBuffer(1, ctx.sampleRate*duration, ctx.sampleRate); var data=buffer.getChannelData(0); for(var i=0;i<data.length;i++){ data[i]=(Math.random()*2-1)*(1-i/data.length)*0.2; } var src=ctx.createBufferSource(); src.buffer=buffer; var f=ctx.createBiquadFilter(); f.type='lowpass'; f.frequency.value=1200; src.connect(f); f.connect(ctx.destination); src.start(); }catch(e){} }

  var pdfjsSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js';
  var pdfWorkerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
  var pageFlipCss = document.createElement('link'); pageFlipCss.rel='stylesheet'; pageFlipCss.href='https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/css/page-flip.min.css'; document.head.appendChild(pageFlipCss);
  var pageFlipSrc = 'https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js';
  function loadScript(src){ return new Promise(function(resolve){ var s=document.createElement('script'); s.src=src; s.onload=resolve; document.head.appendChild(s); }); }

  function initFlip(url){
    if(!isPdf(url)){ if(noteEl){ noteEl.classList.remove('hidden'); noteEl.textContent='Preview tersedia untuk file PDF.'; } flipEl.innerHTML=''; return; }
    noteEl && noteEl.classList.add('hidden');
    Promise.resolve()
      .then(function(){ return loadScript(pdfjsSrc); })
      .then(function(){ window['pdfjsLib'].GlobalWorkerOptions.workerSrc = pdfWorkerSrc; return loadScript(pageFlipSrc); })
      .then(function(){ return window['pdfjsLib'].getDocument({ url: url }).promise; })
      .catch(function(){ return fetch(url, { mode:'cors', credentials: 'same-origin' }).then(function(r){ if(!r.ok) throw new Error('HTTP '+r.status); return r.arrayBuffer(); }).then(function(ab){ return window['pdfjsLib'].getDocument({ data: ab }).promise; }); })
      .then(function(pdf){ var images = []; var tasks=[]; var maxPages = Math.min(pdf.numPages, 30);
        for(let p=1; p<=maxPages; p++){
          const pageNum = p;
          tasks.push(pdf.getPage(pageNum).then(function(page){
            var base = page.getViewport({ scale: 1 });
            var targetW = 800 * (window.devicePixelRatio || 1);
            var scale = Math.min(4, targetW / base.width);
            var viewport = page.getViewport({ scale: scale });
            var canvas = document.createElement('canvas'); var ctx = canvas.getContext('2d');
            canvas.width = Math.floor(viewport.width); canvas.height = Math.floor(viewport.height);
            if(ctx){ ctx.imageSmoothingEnabled = false; ctx.imageSmoothingQuality = 'high'; }
            return page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function(){ images[pageNum-1] = canvas.toDataURL('image/png'); });
          }));
        }
        return Promise.all(tasks).then(function(){ return images.filter(Boolean); });
      })
      .then(function(images){
        flipEl.innerHTML='';
        var pf = new window['St'].PageFlip(flipEl, { width: 800, height: 1100, size:'stretch', maxShadowOpacity:0.5, usePortrait:false, showCover:true, mobileScrollSupport:false, flippingTime:700 });
        pf.loadFromImages(images);
        var currentZoom = 1.0; var isSoundEnabled = true;
        function showCover(){ coverOverlay && coverOverlay.classList.remove('hidden'); flipEl.style.visibility='hidden'; }
        function hideCover(){ coverOverlay && coverOverlay.classList.add('hidden'); flipEl.style.visibility='visible'; }
        function updateInfo(){ var idx = pf.getCurrentPageIndex(); var txt = idx===0 ? 'Cover' : ('Hal '+idx+' - '+(idx+1)); pageInfo && (pageInfo.textContent = txt); var total = pf.getPageCount(); prevBtn && (prevBtn.disabled = (idx===0)); nextBtn && (nextBtn.disabled = (idx>=total-1)); if(idx===0) showCover(); else hideCover(); }
        function applyZoom(){ zoomInfo && (zoomInfo.textContent = Math.round(currentZoom*100)+'%'); var flip = document.getElementById('ebook_flipbook_edit'); if(flip){ flip.style.transformOrigin='center center'; flip.style.transform='scale('+currentZoom+')'; } }
        if(coverImg){ var coverUrl = coverImg.getAttribute('data-cover-url'); if(coverUrl){ coverImg.src = coverUrl; } else if(images.length){ coverImg.src = images[0]; } }
        showCover(); applyZoom(); updateInfo();
        pf.on('flip', function(){ if(isSoundEnabled) playPaperSound(); updateInfo(); applyZoom(); });
        prevBtn && prevBtn.addEventListener('click', function(){ var idx = pf.getCurrentPageIndex(); if(idx===0){ return; } pf.flipPrev(); });
        nextBtn && nextBtn.addEventListener('click', function(){ var idx = pf.getCurrentPageIndex(); if(idx===0){ hideCover(); } pf.flipNext(); });
        zin && zin.addEventListener('click', function(){ currentZoom = Math.min(2.0, currentZoom+0.1); applyZoom(); });
        zout && zout.addEventListener('click', function(){ currentZoom = Math.max(0.5, currentZoom-0.1); applyZoom(); });
        soundBtn && soundBtn.addEventListener('click', function(){ isSoundEnabled = !isSoundEnabled; soundBtn.style.opacity = isSoundEnabled ? '1' : '0.5'; });
      })
      .catch(function(err){ if(noteEl){ noteEl.classList.remove('hidden'); noteEl.textContent = 'Gagal memuat preview: '+(err && err.message ? err.message : err); } });
  }

  var initialUrl = flipEl ? flipEl.getAttribute('data-file-url') : '';
  if(initialUrl){ initFlip(initialUrl); }
  if(fileInput){ fileInput.addEventListener('change', function(){ var f = fileInput.files && fileInput.files[0]; if(!f){ return; } var obj = URL.createObjectURL(f); initFlip(obj); }); }
})();
</script>
@endsection
