@extends('components.layout.member')
@section('page_title','Dashboard Member')
@section('content')
<div class="space-y-6">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-ui.stat-card label="Kursus Dimiliki" value="{{ $courseCount }}" icon="fa-solid fa-book-open" />
    <x-ui.stat-card label="E-book Dimiliki" value="{{ $ebookCount }}" icon="fa-solid fa-book" />
    <x-ui.stat-card label="Notifikasi" value="0" icon="fa-regular fa-bell" />
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="glass p-6 rounded-lg">
      <h3 class="text-lg font-semibold text-yellow-400 mb-4">Kursus Terakhir</h3>
      <x-ui.table>
        <x-slot name="header">
          <tr>
            <th class="text-left py-3 px-4">Kursus</th>
            <th class="text-center py-3 px-4">Enrolled</th>
          </tr>
        </x-slot>
        @forelse($latestCourses as $en)
        <tr class="border-b border-white/10">
          <td class="py-3 px-4">{{ optional($en->course)->title }}</td>
          <td class="text-center py-3 px-4">{{ optional($en->enrolled_at)->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="2" class="py-6 text-center text-white/60">Belum ada kursus</td>
        </tr>
        @endforelse
      </x-ui.table>
    </div>

    <div class="glass p-6 rounded-lg">
      <h3 class="text-lg font-semibold text-yellow-400 mb-4">E-book Terakhir</h3>
      <x-ui.table>
        <x-slot name="header">
          <tr>
            <th class="text-left py-3 px-4">E-book</th>
            <th class="text-center py-3 px-4">Dibeli</th>
          </tr>
        </x-slot>
        @forelse($latestEbooks as $lib)
        <tr class="border-b border-white/10">
          <td class="py-3 px-4">{{ optional($lib->ebook)->title }}</td>
          <td class="text-center py-3 px-4">{{ optional($lib->purchased_at)->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="2" class="py-6 text-center text-white/60">Belum ada e-book</td>
        </tr>
        @endforelse
      </x-ui.table>
    </div>
  </div>
</div>
@endsection

