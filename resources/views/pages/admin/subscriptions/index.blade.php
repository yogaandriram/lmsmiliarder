@extends('components.layout.admin')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Berlangganan</h2>

<div class="space-y-8">
  <div class="glass p-6 rounded">
    <h3 class="text-lg font-semibold text-yellow-400 mb-4">Langganan Kursus</h3>
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-3 px-4">User</th>
          <th class="text-left py-3 px-4">Kursus</th>
          <th class="text-left py-3 px-4">Tanggal</th>
          <th class="text-right py-3 px-4">Aksi</th>
        </tr>
      </x-slot>
      @forelse($courseSubs as $s)
        <tr class="border-b border-white/10">
          <td class="py-3 px-4">{{ $s->user->name ?? 'User #'.$s->user_id }}</td>
          <td class="py-3 px-4">{{ $s->course->title ?? 'Kursus #'.$s->course_id }}</td>
          <td class="py-3 px-4">{{ optional($s->enrolled_at)->format('d M Y H:i') ?? '-' }}</td>
          <td class="py-3 px-4 text-right">
            @php $trx = null; @endphp
            <x-ui.btn-secondary size="sm" href="{{ route('admin.transactions.index') }}" icon="fa-solid fa-eye">Transaksi</x-ui.btn-secondary>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="py-6 px-4 text-center text-white/60">Belum ada langganan kursus.</td>
        </tr>
      @endforelse
    </x-ui.table>
    <div class="mt-4">{{ $courseSubs->links() }}</div>
  </div>

  <div class="glass p-6 rounded">
    <h3 class="text-lg font-semibold text-yellow-400 mb-4">Koleksi E-book</h3>
    <x-ui.table>
      <x-slot name="header">
        <tr>
          <th class="text-left py-3 px-4">User</th>
          <th class="text-left py-3 px-4">E-book</th>
          <th class="text-left py-3 px-4">Tanggal</th>
          <th class="text-right py-3 px-4">Aksi</th>
        </tr>
      </x-slot>
      @forelse($ebookSubs as $s)
        <tr class="border-b border-white/10">
          <td class="py-3 px-4">{{ $s->user->name ?? 'User #'.$s->user_id }}</td>
          <td class="py-3 px-4">{{ $s->ebook->title ?? 'E-book #'.$s->ebook_id }}</td>
          <td class="py-3 px-4">{{ optional($s->created_at)->format('d M Y H:i') ?? '-' }}</td>
          <td class="py-3 px-4 text-right">
            <x-ui.btn-secondary size="sm" href="{{ route('admin.transactions.index') }}" icon="fa-solid fa-eye">Transaksi</x-ui.btn-secondary>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="py-6 px-4 text-center text-white/60">Belum ada koleksi e-book.</td>
        </tr>
      @endforelse
    </x-ui.table>
    <div class="mt-4">{{ $ebookSubs->links() }}</div>
  </div>
</div>
@endsection

