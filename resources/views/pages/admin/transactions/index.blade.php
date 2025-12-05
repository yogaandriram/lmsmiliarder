@extends('components.layout.admin')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Semua Transaksi</h2>

<x-ui.table>
  <x-slot name="header">
    <tr>
      <th class="text-left py-3 px-4">ID</th>
      <th class="text-left py-3 px-4">User</th>
      <th class="text-left py-3 px-4">Produk</th>
      <th class="text-left py-3 px-4">Tipe</th>
      <th class="text-left py-3 px-4">Tanggal</th>
      <th class="text-left py-3 px-4">Status</th>
      <th class="text-right py-3 px-4">Nominal</th>
      <th class="text-right py-3 px-4">Aksi</th>
    </tr>
  </x-slot>
  @foreach($transactions as $t)
    <tr class="border-b border-white/10">
      <td class="py-3 px-4">#{{ $t->id }}</td>
      <td class="py-3 px-4">{{ $t->user->name ?? 'User #'.$t->user_id }}</td>
      @php $first = $t->details->first(); @endphp
      <td class="py-3 px-4">{{ $first ? ($first->product_type==='course' ? ($first->course->title ?? 'Kursus') : ($first->ebook->title ?? 'E-book')) : '-' }}</td>
      <td class="py-3 px-4">{{ $first ? ucfirst($first->product_type) : '-' }}</td>
      <td class="py-3 px-4">{{ optional($t->transaction_time)->format('d M Y H:i') ?? '-' }}</td>
      <td class="py-3 px-4">
        <span class="px-2 py-1 rounded-full text-xs 
          @if($t->payment_status==='success') bg-green-500/20 text-green-300 
          @elseif($t->payment_status==='failed') bg-red-500/20 text-red-300 
          @else bg-yellow-500/20 text-yellow-300 @endif">
          {{ ucfirst($t->payment_status) }}
        </span>
      </td>
      <td class="py-3 px-4 text-right">Rp {{ number_format((int)($t->payable_amount ?? $t->final_amount ?? $t->total_amount),0,',','.') }}</td>
      <td class="py-3 px-4 text-right">
        <x-ui.btn-secondary href="{{ route('admin.transactions.show', $t) }}" size="sm" icon="fa-solid fa-eye">Detail</x-ui.btn-secondary>
      </td>
    </tr>
  @endforeach
</x-ui.table>

<div class="mt-4">{{ $transactions->links() }}</div>
@endsection
