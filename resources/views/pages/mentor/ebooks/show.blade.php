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

            <div class="glass p-6 rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-yellow-400">Preview E-book</h3>
                    <div class="text-xs text-white/60">Geser halaman seperti buku, lengkap dengan suara</div>
                </div>
                <div id="ebook_preview_wrapper" class="relative w-full overflow-hidden">
                    <div id="ebook_flipbook" data-file-url="{{ $ebook->file_url }}" class="w-full h-[70vh] bg-transparent rounded overflow-hidden"></div>
                    <div id="ebook_cover_overlay" class="absolute inset-0 grid place-items-center bg-transparent z-10 hidden" style="pointer-events:none">
                        <img id="ebook_cover_image" data-cover-url="{{ $ebook->cover_image_url }}" src="" alt="Cover" class="max-h-[68vh] rounded shadow-xl" />
                    </div>
                </div>
                <style>
                  #ebook_flipbook img{ image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges; }
                </style>
                <div id="ebook_preview_note" class="mt-3 text-white/60 text-sm hidden"></div>
                <div class="mt-3 flex items-center gap-3">
                    <x-ui.btn-secondary id="ebook_prev" icon="fa-solid fa-chevron-left">Prev</x-ui.btn-secondary>
                    <span id="ebook_page_info" class="text-sm text-white/70">...</span>
                    <x-ui.btn-secondary id="ebook_next" icon="fa-solid fa-chevron-right">Next</x-ui.btn-secondary>
                    <div class="ml-4 flex items-center gap-2">
                        <x-ui.btn-secondary id="ebook_zoom_out" icon="fa-solid fa-magnifying-glass-minus"/>
                        <span id="ebook_zoom_info" class="text-xs text-white/60">100%</span>
                        <x-ui.btn-secondary id="ebook_zoom_in" icon="fa-solid fa-magnifying-glass-plus"/>
                        <x-ui.btn-secondary id="ebook_sound" icon="fa-solid fa-volume-high"/>
                    </div>
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
                    <x-ui.btn-secondary href="#" icon="fa-solid fa-chart-line" class="w-full">Lihat Analitik</x-ui.btn-secondary>
                </div>
            </div>

            <!-- Ebook Settings -->
            <div class="glass p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-400 mb-4">Pengaturan E-book</h3>
                <div class="space-y-3">
                    @php
                      $statusClass = $ebook->status === 'published'
                        ? 'bg-green-500/20 text-green-300'
                        : ($ebook->status === 'draft'
                          ? 'bg-gray-500/20 text-gray-300'
                          : 'bg-orange-500/20 text-orange-300');
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="text-white/90">Status</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">{{ ucfirst($ebook->status) }}</span>
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
<script>
(function(){
  var flipEl = document.getElementById('ebook_flipbook');
  var fileUrl = flipEl ? flipEl.getAttribute('data-file-url') : '';
  var noteEl = document.getElementById('ebook_preview_note');
  function isPdf(url){ return /\.pdf($|\?)/i.test(url || ''); }
  function playPaperSound(){ try{
    var ctx = new (window.AudioContext || window.webkitAudioContext)();
    var duration = 0.25;
    var buffer = ctx.createBuffer(1, ctx.sampleRate * duration, ctx.sampleRate);
    var data = buffer.getChannelData(0);
    for(var i=0;i<data.length;i++){ data[i] = (Math.random()*2-1) * (1 - i/data.length) * 0.2; }
    var source = ctx.createBufferSource();
    source.buffer = buffer;
    var filter = ctx.createBiquadFilter();
    filter.type = 'lowpass'; filter.frequency.value = 1200;
    source.connect(filter); filter.connect(ctx.destination);
    source.start();
  }catch(e){}
  }

  if(!isPdf(fileUrl)){
    if(noteEl){ noteEl.classList.remove('hidden'); noteEl.textContent = 'Preview tersedia untuk file PDF. Saat ini URL file bukan PDF.'; }
    return;
  }

  var pdfjsSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js';
  var pdfWorkerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
  var pageFlipCss = document.createElement('link'); pageFlipCss.rel='stylesheet'; pageFlipCss.href='https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/css/page-flip.min.css'; document.head.appendChild(pageFlipCss);
  var pageFlipSrc = 'https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js';
  function loadScript(src){ return new Promise(function(resolve){ var s=document.createElement('script'); s.src=src; s.onload=resolve; document.head.appendChild(s); }); }
  Promise.resolve()
    .then(function(){ return loadScript(pdfjsSrc); })
    .then(function(){ window['pdfjsLib'].GlobalWorkerOptions.workerSrc = pdfWorkerSrc; return loadScript(pageFlipSrc); })
    .then(function(){ return window['pdfjsLib'].getDocument({ url: fileUrl }).promise; })
    .catch(function(){ return fetch(fileUrl, { mode: 'cors' }).then(function(r){ if(!r.ok) throw new Error('HTTP '+r.status); return r.arrayBuffer(); }).then(function(ab){ return window['pdfjsLib'].getDocument({ data: ab }).promise; }); })
    .then(function(pdf){ var images = []; var tasks=[]; var maxPages = Math.min(pdf.numPages, 30);
      for(let p=1; p<=maxPages; p++){
        const pageNum = p;
        tasks.push(pdf.getPage(pageNum).then(function(page){
          var base = page.getViewport({ scale: 1 });
          var targetW = 800 * (window.devicePixelRatio || 1);
          var scale = Math.min(4, targetW / base.width);
          var viewport = page.getViewport({ scale: scale });
          var canvas = document.createElement('canvas');
          var ctx = canvas.getContext('2d');
          canvas.width = Math.floor(viewport.width);
          canvas.height = Math.floor(viewport.height);
          if(ctx){ ctx.imageSmoothingEnabled = false; ctx.imageSmoothingQuality = 'high'; }
          return page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function(){ images[pageNum-1] = canvas.toDataURL('image/png'); });
        }));
      }
      return Promise.all(tasks).then(function(){ return images.filter(Boolean); });
    })
    .then(function(images){
      var imagesCache = images.slice();
      var pf = new window['St'].PageFlip(flipEl, { width: 800, height: 1100, size: 'stretch', maxShadowOpacity: 0.5, usePortrait: false, showCover: true, mobileScrollSupport: false, flippingTime: 700 });
      pf.loadFromImages(images);
      var currentZoom = 1.0; var isSoundEnabled = true; var zoomInfo = document.getElementById('ebook_zoom_info'); var pageInfo = document.getElementById('ebook_page_info'); var wrapper = document.getElementById('ebook_preview_wrapper');
      var coverOverlay = document.getElementById('ebook_cover_overlay'); var coverImg = document.getElementById('ebook_cover_image');
      if(coverImg){ var coverUrl = coverImg.getAttribute('data-cover-url'); if(coverUrl){ coverImg.src = coverUrl; } else if(images.length){ coverImg.src = images[0]; } }
      function showCover(){ if(coverOverlay){ coverOverlay.classList.remove('hidden'); } flipEl.style.visibility = 'hidden'; }
      function hideCover(){ if(coverOverlay){ coverOverlay.classList.add('hidden'); } flipEl.style.visibility = 'visible'; }
      function updateInfo(){ var idx = pf.getCurrentPageIndex(); var txt = idx === 0 ? 'Cover' : ('Hal ' + idx + ' - ' + (idx+1)); pageInfo.textContent = txt; var total = pf.getPageCount(); if(prevBtn) prevBtn.disabled = (idx === 0); if(nextBtn) nextBtn.disabled = (idx >= total-1); if(idx === 0) showCover(); else hideCover(); }
      function applyZoom(){ var idx = pf.getCurrentPageIndex(); var shift = (idx === 0 ? 0 : 0); zoomInfo.textContent = Math.round(currentZoom*100)+'%'; var flip = document.getElementById('ebook_flipbook'); if(flip){ flip.style.transformOrigin = 'center center'; flip.style.transform = 'scale('+currentZoom+') translateX('+shift+'%)'; } }
      showCover(); applyZoom(); updateInfo();
      pf.on('flip', function(){ if(isSoundEnabled) playPaperSound(); updateInfo(); applyZoom(); });
      var prevBtn = document.getElementById('ebook_prev'); var nextBtn = document.getElementById('ebook_next'); var zin = document.getElementById('ebook_zoom_in'); var zout = document.getElementById('ebook_zoom_out'); var soundBtn = document.getElementById('ebook_sound');
      if(prevBtn) prevBtn.addEventListener('click', function(){ var idx = pf.getCurrentPageIndex(); if(idx === 0){ return; } pf.flipPrev(); });
      if(nextBtn) nextBtn.addEventListener('click', function(){ var idx = pf.getCurrentPageIndex(); if(idx === 0){ hideCover(); } pf.flipNext(); });
      if(zin) zin.addEventListener('click', function(){ currentZoom = Math.min(2.0, currentZoom+0.1); applyZoom(); });
      if(zout) zout.addEventListener('click', function(){ currentZoom = Math.max(0.5, currentZoom-0.1); applyZoom(); });
      if(soundBtn) soundBtn.addEventListener('click', function(){ isSoundEnabled = !isSoundEnabled; soundBtn.style.opacity = isSoundEnabled ? '1' : '0.5'; });
    })
    .catch(function(err){ if(noteEl){ noteEl.classList.remove('hidden'); noteEl.textContent = 'Gagal memuat preview: '+(err && err.message ? err.message : err); } });
})();
</script>
@endsection
