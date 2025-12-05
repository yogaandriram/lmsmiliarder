@props([
  'action' => request()->url(),
  'startName' => 'date_start',
  'endName' => 'date_end',
  'startValue' => request()->query('date_start'),
  'endValue' => request()->query('date_end'),
])

@php $__drid = 'dr_'.uniqid(); @endphp

<form method="GET" action="{{ $action }}" class="relative inline-block">
  <input type="hidden" name="{{ $startName }}" id="{{ $__drid }}_start" value="{{ $startValue }}">
  <input type="hidden" name="{{ $endName }}" id="{{ $__drid }}_end" value="{{ $endValue }}">

  <button type="button" id="{{ $__drid }}_toggle" class="glass px-4 py-2 rounded inline-flex items-center gap-2">
    <span id="{{ $__drid }}_label" class="text-white">{{ $startValue && $endValue ? \Illuminate\Support\Carbon::parse($startValue)->format('d M y').' - '.\Illuminate\Support\Carbon::parse($endValue)->format('d M y') : 'Pilih Rentang Tanggal' }}</span>
    <i class="fa-solid fa-calendar-days text-white/80"></i>
  </button>

  <div id="{{ $__drid }}_panel" class="absolute z-50 mt-2 w-[720px] hidden">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 glass p-4 rounded">
      <div class="md:col-span-1">
        <ul class="space-y-2 text-white/90">
          <li><button type="button" data-preset="today" class="w-full text-left px-3 py-2 rounded bg-white/5 hover:bg-white/10">Today</button></li>
          <li><button type="button" data-preset="yesterday" class="w-full text-left px-3 py-2 rounded bg-white/5 hover:bg-white/10">Yesterday</button></li>
          <li><button type="button" data-preset="this_week" class="w-full text-left px-3 py-2 rounded bg-white/5 hover:bg-white/10">This week</button></li>
          <li><button type="button" data-preset="last_week" class="w-full text-left px-3 py-2 rounded bg-white/5 hover:bg-white/10">Last 1 week</button></li>
          <li><button type="button" data-preset="last_2_weeks" class="w-full text-left px-3 py-2 rounded bg-white/5 hover:bg-white/10">Last 2 weeks</button></li>
          <li><button type="button" data-preset="this_month" class="w-full text-left px-3 py-2 rounded bg-white/5 hover:bg-white/10">This month</button></li>
          <li><button type="button" data-preset="last_month" class="w-full text-left px-3 py-2 rounded bg-white/5 hover:bg-white/10">1 bulan terakhir</button></li>
        </ul>
      </div>
      <div class="md:col-span-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-white/70 text-sm mb-1">Mulai</label>
            <input type="date" id="{{ $__drid }}_start_input" class="w-full px-3 py-2 rounded bg-white/10 border border-white/20 text-white" value="{{ $startValue }}">
          </div>
          <div>
            <label class="block text-white/70 text-sm mb-1">Selesai</label>
            <input type="date" id="{{ $__drid }}_end_input" class="w-full px-3 py-2 rounded bg-white/10 border border-white/20 text-white" value="{{ $endValue }}">
          </div>
        </div>
        <div class="mt-4 flex justify-end gap-2">
          <button type="button" id="{{ $__drid }}_clear" class="px-3 py-2 rounded bg-white/5 text-white/80 hover:bg-white/10">Clear</button>
          <x-ui.btn-primary type="submit">Terapkan</x-ui.btn-primary>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
(function(){
  const id = "{{ $__drid }}";
  const btn = document.getElementById(id+"_toggle");
  const panel = document.getElementById(id+"_panel");
  const startHidden = document.getElementById(id+"_start");
  const endHidden = document.getElementById(id+"_end");
  const startInput = document.getElementById(id+"_start_input");
  const endInput = document.getElementById(id+"_end_input");
  const label = document.getElementById(id+"_label");
  const clearBtn = document.getElementById(id+"_clear");

  function fmt(d){
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const dt = new Date(d);
    const dd = ("0"+dt.getDate()).slice(-2);
    const mm = months[dt.getMonth()];
    const yy = (""+dt.getFullYear()).slice(-2);
    return `${dd} ${mm} ${yy}`;
  }

  function updateLabel(){
    if (startHidden.value && endHidden.value) {
      label.textContent = fmt(startHidden.value)+" - "+fmt(endHidden.value);
    } else {
      label.textContent = 'Pilih Rentang Tanggal';
    }
  }

  function setRange(s,e){
    startHidden.value = s; endHidden.value = e;
    startInput.value = s; endInput.value = e;
    updateLabel();
  }

  btn.addEventListener('click', function(){ panel.classList.toggle('hidden'); });
  document.addEventListener('click', function(ev){
    if (!panel.contains(ev.target) && !btn.contains(ev.target)) { panel.classList.add('hidden'); }
  });

  panel.querySelectorAll('[data-preset]').forEach(function(el){
    el.addEventListener('click', function(){
      const p = el.getAttribute('data-preset');
      const now = new Date();
      const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
      function iso(d){ return new Date(d.getTime()-d.getTimezoneOffset()*60000).toISOString().slice(0,10); }
      let s = iso(today), e = iso(today);
      if (p==='yesterday') { const y = new Date(today); y.setDate(y.getDate()-1); s=iso(y); e=iso(y); }
      if (p==='this_week') {
        const day = today.getDay();
        const monday = new Date(today); monday.setDate(monday.getDate() - ((day+6)%7));
        const sunday = new Date(monday); sunday.setDate(sunday.getDate()+6);
        s=iso(monday); e=iso(sunday);
      }
      if (p==='last_week') {
        const day = today.getDay();
        const monday = new Date(today); monday.setDate(monday.getDate() - ((day+6)%7) - 7);
        const sunday = new Date(monday); sunday.setDate(sunday.getDate()+6);
        s=iso(monday); e=iso(sunday);
      }
      if (p==='last_2_weeks') {
        const end = new Date(today); const start = new Date(today); start.setDate(start.getDate()-13);
        s=iso(start); e=iso(end);
      }
      if (p==='this_month') {
        const start = new Date(today.getFullYear(), today.getMonth(), 1);
        const end = new Date(today.getFullYear(), today.getMonth()+1, 0);
        s=iso(start); e=iso(end);
      }
      if (p==='last_month') {
        const start = new Date(today.getFullYear(), today.getMonth()-1, 1);
        const end = new Date(today.getFullYear(), today.getMonth(), 0);
        s=iso(start); e=iso(end);
      }
      setRange(s,e);
    });
  });

  startInput.addEventListener('change', function(){ startHidden.value = startInput.value; updateLabel(); });
  endInput.addEventListener('change', function(){ endHidden.value = endInput.value; updateLabel(); });
  clearBtn.addEventListener('click', function(){ startHidden.value=''; endHidden.value=''; startInput.value=''; endInput.value=''; updateLabel(); });
})();
</script>

