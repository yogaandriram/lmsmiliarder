@props(['paginator'])

@php
  $pg = $paginator;
  $isLengthAware = $pg instanceof \Illuminate\Pagination\LengthAwarePaginator;
  $prevUrl = $pg->previousPageUrl();
  $nextUrl = $pg->nextPageUrl();
  $current = method_exists($pg,'currentPage') ? $pg->currentPage() : 1;
  $last = $isLengthAware ? $pg->lastPage() : null;
  $window = 2;
  $range = function($start,$end){ return range(max(1,$start), max(1,$end)); };
@endphp

<nav class="flex items-center justify-between mt-4">
  <div>
    <a href="{{ $prevUrl ?: '#' }}" class="px-3 py-2 rounded bg-white/5 text-white/80 {{ $prevUrl ? 'hover:bg-white/10' : 'opacity-50 cursor-not-allowed' }}">
      <i class="fa-solid fa-chevron-left"></i> Prev
    </a>
  </div>

  <div>
    @if($isLengthAware)
      <ul class="flex items-center gap-2">
        @php $pages = []; @endphp
        @for($i = 1; $i <= $last; $i++)
          @if($i === 1 || $i === $last || ($i >= $current-$window && $i <= $current+$window))
            @php $pages[] = $i; @endphp
          @endif
        @endfor
        @php $pages = array_values(array_unique($pages)); sort($pages); @endphp
        @foreach($pages as $index => $i)
          @if($index > 0 && ($pages[$index-1] + 1) < $i)
            <li class="px-2 text-white/60">…</li>
          @endif
          <li>
            <a href="{{ $pg->url($i) }}" class="min-w-10 text-center px-3 py-2 rounded {{ $i===$current ? 'bg-linear-to-b from-yellow-400 to-amber-500 text-black' : 'bg-white/5 text-white/80 hover:bg-white/10' }}">{{ $i }}</a>
          </li>
        @endforeach
      </ul>
    @else
      <span class="inline-flex items-center justify-center min-w-10 px-3 py-2 rounded bg-linear-to-b from-yellow-400 to-amber-500 text-black font-semibold">{{ $current }}</span>
    @endif
  </div>

  <div>
    <a href="{{ $nextUrl ?: '#' }}" class="px-3 py-2 rounded bg-white/5 text-white/80 {{ $nextUrl ? 'hover:bg-white/10' : 'opacity-50 cursor-not-allowed' }}">
      Next <i class="fa-solid fa-chevron-right"></i>
    </a>
  </div>
</nav>
