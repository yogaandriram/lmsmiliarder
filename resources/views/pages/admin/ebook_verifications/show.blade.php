@extends('components.layout.admin')
@section('page_title', 'Detail Verifikasi E-book')

@section('content')
<div class="space-y-6">
  <div class="space-y-6">
    <div class="glass p-6 rounded-lg">
      <div class="flex items-center justify-between mb-2">
        <h3 class="text-lg font-semibold text-yellow-400">Preview E-book</h3>
        <div class="text-xs text-white/60">Geser halaman seperti buku, lengkap dengan suara</div>
      </div>
      <div id="ebook_preview_wrapper_admin" class="relative w-full overflow-hidden">
        <div id="ebook_flipbook_admin" data-file-url="{{ $ebook->file_url }}" class="w-full h-[70vh] bg-transparent rounded overflow-hidden"></div>
        <div id="ebook_cover_overlay_admin" class="absolute inset-0 grid place-items-center bg-transparent z-10 hidden" style="pointer-events:none">
          <img id="ebook_cover_image_admin" data-cover-url="{{ $ebook->cover_image_url }}" src="" alt="Cover" class="max-h-[68vh] rounded shadow-xl" />
        </div>
      </div>
      <div class="mt-3 flex items-center gap-3">
        <x-ui.btn-secondary id="ebook_prev_admin" icon="fa-solid fa-chevron-left">Prev</x-ui.btn-secondary>
        <span id="ebook_page_info_admin" class="text-sm text-white/70">...</span>
        <x-ui.btn-secondary id="ebook_next_admin" icon="fa-solid fa-chevron-right">Next</x-ui.btn-secondary>
        <div class="ml-4 flex items-center gap-2">
          <x-ui.btn-secondary id="ebook_zoom_out_admin" icon="fa-solid fa-magnifying-glass-minus"/>
          <span id="ebook_zoom_info_admin" class="text-xs text-white/60">100%</span>
          <x-ui.btn-secondary id="ebook_zoom_in_admin" icon="fa-solid fa-magnifying-glass-plus"/>
          <x-ui.btn-secondary id="ebook_sound_admin" icon="fa-solid fa-volume-high"/>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class="glass p-6 rounded-lg">
      <h3 class="text-lg font-semibold text-yellow-400 mb-4">Aksi</h3>
      <div class="space-y-3">
        <x-ui.btn-secondary href="{{ $ebook->file_url }}" target="_blank" icon="fa-solid fa-download" class="w-full">Download File</x-ui.btn-secondary>
        <form method="POST" action="{{ route('admin.ebook_verifications.approve', $ebook) }}">
          @csrf
          <x-ui.btn-primary type="submit" icon="fa-solid fa-check" class="w-full">Setujui</x-ui.btn-primary>
        </form>
        <form method="POST" action="{{ route('admin.ebook_verifications.reject', $ebook) }}">
          @csrf
          <x-ui.btn-primary type="submit" variant="danger" icon="fa-solid fa-xmark" class="w-full">Tolak</x-ui.btn-primary>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  #ebook_flipbook_admin img{ image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges; }
</style>
@endpush

@push('scripts')
<script>
(function(){
  var flipEl = document.getElementById('ebook_flipbook_admin');
  var fileUrl = flipEl ? flipEl.getAttribute('data-file-url') : '';
  var wrapper = document.getElementById('ebook_preview_wrapper_admin');
  var coverOverlay = document.getElementById('ebook_cover_overlay_admin');
  var coverImg = document.getElementById('ebook_cover_image_admin');
  var prevBtn = document.getElementById('ebook_prev_admin');
  var nextBtn = document.getElementById('ebook_next_admin');
  var zin = document.getElementById('ebook_zoom_in_admin');
  var zout = document.getElementById('ebook_zoom_out_admin');
  var soundBtn = document.getElementById('ebook_sound_admin');
  var zoomInfo = document.getElementById('ebook_zoom_info_admin');
  var pageInfo = document.getElementById('ebook_page_info_admin');
  var currentZoom = 1.0; var isSoundEnabled = true;
  function playPaperSound(){ try{ var ctx=new (window.AudioContext||window.webkitAudioContext)(); var duration=0.2; var buffer=ctx.createBuffer(1, ctx.sampleRate*duration, ctx.sampleRate); var data=buffer.getChannelData(0); for(var i=0;i<data.length;i++){ data[i]=(Math.random()*2-1)*(1-i/data.length)*0.25; } var src=ctx.createBufferSource(); src.buffer=buffer; var f=ctx.createBiquadFilter(); f.type='lowpass'; f.frequency.value=1200; src.connect(f); f.connect(ctx.destination); src.start(); }catch(e){} }
  function showCover(){ if(coverOverlay){ coverOverlay.classList.remove('hidden'); } flipEl.style.visibility = 'hidden'; }
  function hideCover(){ if(coverOverlay){ coverOverlay.classList.add('hidden'); } flipEl.style.visibility = 'visible'; }
  if(coverImg){ var cu = coverImg.getAttribute('data-cover-url'); if(cu){ coverImg.src = cu; } }
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
        tasks.push(pdf.getPage(pageNum).then(function(page){ var base = page.getViewport({ scale: 1 }); var targetW = 800 * (window.devicePixelRatio || 1); var scale = Math.min(4, targetW / base.width); var viewport = page.getViewport({ scale: scale }); var canvas = document.createElement('canvas'); var ctx = canvas.getContext('2d'); canvas.width = Math.floor(viewport.width); canvas.height = Math.floor(viewport.height); if(ctx){ ctx.imageSmoothingEnabled=false; ctx.imageSmoothingQuality='high'; } return page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function(){ images[pageNum-1] = canvas.toDataURL('image/png'); }); }));
      }
      return Promise.all(tasks).then(function(){ return images.filter(Boolean); });
    })
    .then(function(images){ var pf = new window['St'].PageFlip(flipEl, { width: 800, height: 1100, size: 'stretch', maxShadowOpacity: 0.5, usePortrait: false, showCover: true, mobileScrollSupport: false, flippingTime: 700 }); pf.loadFromImages(images);
      function updateInfo(){ var idx = pf.getCurrentPageIndex(); var txt = idx === 0 ? 'Cover' : ('Hal ' + idx + ' - ' + (idx+1)); pageInfo.textContent = txt; var total = pf.getPageCount(); if(prevBtn) prevBtn.disabled = (idx === 0); if(nextBtn) nextBtn.disabled = (idx >= total-1); if(idx === 0) showCover(); else hideCover(); }
      function applyZoom(){ zoomInfo.textContent = Math.round(currentZoom*100)+'%'; var flip = document.getElementById('ebook_flipbook_admin'); if(flip){ flip.style.transformOrigin = 'center center'; flip.style.transform = 'scale('+currentZoom+')'; } }
      showCover(); applyZoom(); updateInfo();
      pf.on('flip', function(){ if(isSoundEnabled) playPaperSound(); updateInfo(); applyZoom(); });
      if(prevBtn) prevBtn.addEventListener('click', function(){ var idx = pf.getCurrentPageIndex(); if(idx === 0){ return; } pf.flipPrev(); });
      if(nextBtn) nextBtn.addEventListener('click', function(){ var idx = pf.getCurrentPageIndex(); if(idx === 0){ hideCover(); } pf.flipNext(); });
      if(zin) zin.addEventListener('click', function(){ currentZoom = Math.min(2.0, currentZoom+0.1); applyZoom(); });
      if(zout) zout.addEventListener('click', function(){ currentZoom = Math.max(0.5, currentZoom-0.1); applyZoom(); });
      if(soundBtn) soundBtn.addEventListener('click', function(){ isSoundEnabled = !isSoundEnabled; soundBtn.style.opacity = isSoundEnabled ? '1' : '0.5'; });
    });
})();
</script>
@endpush
