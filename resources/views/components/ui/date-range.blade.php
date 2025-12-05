@props([
  'action' => request()->url(),
  'startName' => 'date_start',
  'endName' => 'date_end',
  'startValue' => request()->query('date_start'),
  'endValue' => request()->query('date_end'),
])

<form method="GET" action="{{ $action }}" class="w-full">
  <div class="flex items-center gap-3">
    <div class="glass p-2 rounded flex items-center gap-2">
      <input type="date" name="{{ $startName }}" value="{{ $startValue }}" class="bg-transparent text-white/90" />
      <span class="text-white/60">–</span>
      <input type="date" name="{{ $endName }}" value="{{ $endValue }}" class="bg-transparent text-white/90" />
      <i class="fa-solid fa-calendar-days text-white/70"></i>
    </div>
    <x-ui.btn-primary type="submit">Terapkan</x-ui.btn-primary>
  </div>
</form>

