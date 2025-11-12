@extends('components.layout.admin')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Transaksi Pending</h2>

<div class="space-y-6">
@forelse($transactions as $trx)
    <div class="glass p-6 rounded">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-yellow-300">Transaksi #{{ $trx->id }}</div>
                <div class="text-sm">User ID: {{ $trx->user_id }} | Status: {{ $trx->payment_status }}</div>
                @if($trx->payment_proof_url)
                    <a class="text-yellow-400" target="_blank" href="{{ $trx->payment_proof_url }}">Lihat Bukti Bayar</a>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.transactions.verify', $trx) }}" class="flex items-center space-x-2">
                @csrf
                <select name="status" class="p-2 rounded bg-white/10 border border-white/20" required>
                    <option value="success">Setujui</option>
                    <option value="failed">Tolak</option>
                </select>
        <x-ui.btn-primary type="submit">Update</x-ui.btn-primary>
            </form>
        </div>
        <div class="mt-4">
            <div class="text-white/80 mb-2">Item:</div>
            <ul class="list-disc ml-5">
            @foreach($trx->details as $d)
                <li>{{ ucfirst($d->product_type) }}: @if($d->product_type==='course') Kursus #{{ $d->course_id }} @else E-book #{{ $d->ebook_id }} @endif - Qty {{ $d->quantity ?? 1 }} - Rp {{ $d->price }}</li>
            @endforeach
            </ul>
        </div>
    </div>
@empty
    <div class="glass p-6 rounded">Tidak ada transaksi pending.</div>
@endforelse
</div>
@endsection