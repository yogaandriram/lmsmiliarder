@extends('components.layout.member')
@section('page_title','Berlangganan')
@section('content')
<div class="space-y-6">
  <div class="glass p-6 rounded">
    <h3 class="text-lg font-semibold text-yellow-400 mb-4">Kursus Berlangganan</h3>
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-3 px-4">Kursus</th>
          <th class="text-center py-3 px-4">Enrolled</th>
        </tr>
      </x-slot>
      @forelse($courses as $en)
        <tr class="border-b border-white/10">
          <td class="py-3 px-4">{{ optional($en->course)->title }}</td>
          <td class="py-3 px-4 text-center">{{ optional($en->enrolled_at)->format('d M Y') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="2" class="py-6 text-center text-white/60">Belum ada kursus</td>
        </tr>
      @endforelse
    </x-ui.table>
    @if($courses->hasPages())
      <div class="mt-4">{{ $courses->links() }}</div>
    @endif
  </div>

  <div class="glass p-6 rounded">
    <h3 class="text-lg font-semibold text-yellow-400 mb-4">E-book Berlangganan</h3>
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-3 px-4">E-book</th>
          <th class="text-center py-3 px-4">Dibeli</th>
        </tr>
      </x-slot>
      @forelse($ebooks as $lib)
        <tr class="border-b border-white/10">
          <td class="py-3 px-4">{{ optional($lib->ebook)->title }}</td>
          <td class="py-3 px-4 text-center">{{ optional($lib->purchased_at)->format('d M Y') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="2" class="py-6 text-center text-white/60">Belum ada e-book</td>
        </tr>
      @endforelse
    </x-ui.table>
    @if($ebooks->hasPages())
      <div class="mt-4">{{ $ebooks->links() }}</div>
    @endif
  </div>
</div>
@endsection
