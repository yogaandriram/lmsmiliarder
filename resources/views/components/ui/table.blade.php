@props([
  'title' => null,
])
<div class="glass p-6 rounded">
  @if($title)
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl">{{ $title }}</h3>
      @isset($actions)
        <div class="flex items-center gap-2">{{ $actions }}</div>
      @endisset
    </div>
  @endif

  <table class="w-full text-left">
    @isset($header)
    <thead>
      {{ $header }}
    </thead>
    @endisset
    <tbody>
      {{ $slot }}
    </tbody>
  </table>
</div>