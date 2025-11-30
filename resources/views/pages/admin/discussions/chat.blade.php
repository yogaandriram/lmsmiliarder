@extends('components.layout.admin')
@section('page_title','Chat: '.$group->group_name)
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-2xl font-bold">{{ $group->course->title }}</h2>
      <p class="text-white/70">Grup: {{ $group->group_name }}</p>
    </div>
    <x-ui.btn-secondary href="{{ route('admin.discussions.index') }}" icon="fa-solid fa-arrow-left">Kembali</x-ui.btn-secondary>
  </div>

  <div class="glass p-0 rounded-lg overflow-hidden relative w-full">
    <div id="chat_messages" data-fetch-url="{{ route('admin.discussions.chat.fetch', $group) }}" class="h-[70vh] overflow-y-auto p-4 space-y-3">
      @foreach($messages as $m)
        <div class="flex items-start gap-3">
          <img src="{{ $m->user->avatar_url ?? 'https://placehold.co/36x36' }}" class="w-9 h-9 rounded-full object-cover" alt="">
          <div class="max-w-[80%]">
            <div class="text-sm text-white/70">{{ $m->user->name }} • {{ \Illuminate\Support\Carbon::parse($m->created_at)->format('H:i') }}</div>
            <div class="p-3 bg-white/10 rounded">{!! nl2br(e($m->content)) !!}</div>
          </div>
        </div>
      @endforeach
    </div>
    <form id="chat_form" method="POST" action="{{ route('admin.discussions.chat.post', $group) }}" class="flex items-center gap-3 p-4 border-t border-white/10 w-full" enctype="multipart/form-data">
      @csrf
      <div class="flex-1 min-w-0">
        <x-ui.crud.input name="content" id="chat_input" placeholder="Ketik pesan..." class="w-full" />
      </div>
      <input type="file" name="attachment" id="chat_attachment" class="hidden" />
      <x-ui.btn-secondary id="emoji_button" type="button" icon="fa-solid fa-face-smile" onclick="toggleEmojiPanel()" title="Emoji" class="shrink-0"></x-ui.btn-secondary>
      <x-ui.btn-secondary type="button" icon="fa-solid fa-paperclip" onclick="document.getElementById('chat_attachment').click()" title="Lampirkan" class="shrink-0"></x-ui.btn-secondary>
      <x-ui.btn-primary type="submit" icon="fa-solid fa-paper-plane" class="shrink-0">Kirim</x-ui.btn-primary>
    </form>
    <div id="emoji_panel" class="absolute bottom-20 left-4 glass p-0 rounded-xl z-50" style="display:none">
      <emoji-picker id="emoji_picker" class="block"></emoji-picker>
    </div>
  </div>

  <script>
    (function(){
      var box = document.getElementById('chat_messages');
      var input = document.getElementById('chat_input');
      var panel = document.getElementById('emoji_panel');
      var pickerLoaded = false;
      var emojiOpen = false;
      var emojiBtn = document.getElementById('emoji_button');
      var pickerListenerBound = false;
      function scrollBottom(){ if(box){ box.scrollTop = box.scrollHeight; } }
      scrollBottom();
      function ensurePicker(){ if(pickerLoaded) return; var s=document.createElement('script'); s.type='module'; s.src='https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js'; s.onload=function(){ pickerLoaded=true; bindPicker(); }; document.head.appendChild(s); }
      function bindPicker(){ if(pickerListenerBound) return; var p=document.getElementById('emoji_picker'); if(p && input){ p.addEventListener('emoji-click', function(e){ input.value=(input.value||'') + (e.detail && e.detail.unicode ? e.detail.unicode : ''); input.focus(); if(panel){ panel.classList.add('hidden'); emojiOpen=false; emojiBtn && emojiBtn.setAttribute('aria-expanded','false'); } }); pickerListenerBound = true; } }
      window.toggleEmojiPanel = function(){ if(!panel) return; ensurePicker(); var hidden = panel.style.display === 'none'; if(hidden){ panel.style.display = 'block'; emojiOpen = true; emojiBtn && emojiBtn.setAttribute('aria-expanded','true'); } else { panel.style.display = 'none'; emojiOpen = false; emojiBtn && emojiBtn.setAttribute('aria-expanded','false'); } };
      bindPicker();
      document.addEventListener('click', function(e){ if(!emojiOpen) return; var path = e.composedPath ? e.composedPath() : []; var insidePanel = path.indexOf(panel) !== -1; var onButton = path.indexOf(emojiBtn) !== -1; if(panel && !insidePanel && !onButton){ panel.style.display = 'none'; emojiOpen=false; emojiBtn && emojiBtn.setAttribute('aria-expanded','false'); } });
      document.addEventListener('keydown', function(e){ if(!emojiOpen) return; if(e.key === 'Escape'){ panel.style.display = 'none'; emojiOpen=false; } });
      setInterval(function(){ var url = box ? box.getAttribute('data-fetch-url') : ''; if(!url) return; fetch(url).then(function(r){ return r.json() }).then(function(d){ var html = ''; for(var i=0;i<d.messages.length;i++){ var m = d.messages[i]; html += '<div class="flex items-start gap-3">' + '<img src="'+(m.avatar || 'https://placehold.co/36x36')+'" class="w-9 h-9 rounded-full object-cover" alt="">' + '<div class="max-w-[80%]">' + '<div class="text-sm text-white/70">'+m.user+' • '+m.time+'</div>' + '<div class="p-3 bg-white/10 rounded">'+(m.content || '').replace(/\n/g,'<br>')+'</div>' + (m.file_url ? (m.mime_type && m.mime_type.indexOf('image') === 0 ? '<div class="mt-2"><img src="'+m.file_url+'" class="max-w-xs rounded" /></div>' : '<div class="mt-2"><a href="'+m.file_url+'" target="_blank" class="underline">'+(m.original_name || 'Lampiran')+'</a></div>') : '') + '</div></div>'; } box.innerHTML = html; scrollBottom(); }); }, 5000);
    })();
  </script>
</div>
@endsection

