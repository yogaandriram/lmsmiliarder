@props([
  'desktop' => false,
])

@if($desktop)
  <button id="theme-toggle" type="button" aria-label="Toggle theme"
          class="ml-2 text-yellow-300/80 hover:text-yellow-300 theme-toggle">
    <i id="theme-toggle-icon" data-theme-icon class="fa-regular text-lg fa-sun"></i>
  </button>
@else
  <button type="button" aria-label="Toggle theme"
          class="md:hidden text-yellow-300/80 hover:text-yellow-300 theme-toggle">
    <i data-theme-icon class="fa-regular text-lg fa-sun"></i>
  </button>
@endif