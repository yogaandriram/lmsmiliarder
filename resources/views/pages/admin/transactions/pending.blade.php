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
                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">
                    <div>Subtotal: <span class="font-semibold">Rp {{ number_format((int)$trx->total_amount,0,',','.') }}</span></div>
                    <div>Diskon: <span class="font-semibold">Rp {{ number_format((int)$trx->discount_amount,0,',','.') }}</span></div>
                    <div>Setelah Diskon: <span class="font-semibold">Rp {{ number_format((int)$trx->final_amount,0,',','.') }}</span></div>
                    <div>Kode Unik: <span class="font-semibold">{{ (int)$trx->unique_code }}</span></div>
                    <div>Nominal Transfer: <span class="font-semibold">Rp {{ number_format((int)$trx->payable_amount,0,',','.') }}</span></div>
                </div>
                @if($trx->payment_proof_url)
                    <div class="mt-2">
                        <a class="text-yellow-400 underline" target="_blank" href="{{ $trx->payment_proof_url }}">Lihat Bukti Pembayaran</a>
                    </div>
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
                <li>
                    {{ ucfirst($d->product_type) }}:
                    @if($d->product_type==='course')
                        {{ $d->course->title ?? ('Kursus #'.$d->course_id) }}
                    @else
                        {{ $d->ebook->title ?? ('E-book #'.$d->ebook_id) }}
                    @endif
                    — Rp {{ number_format((int)$d->price,0,',','.') }}
                </li>
            @endforeach
            </ul>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="glass p-4 rounded">
                    <div class="text-white/80 mb-2">Detail Transfer</div>
                    <div class="text-sm">Nama Pengirim: <span class="font-semibold">{{ $trx->sender_name ?? '-' }}</span></div>
                    <div class="text-sm">No. Rekening Pengirim: <span class="font-semibold">{{ $trx->sender_account_no ?? '-' }}</span></div>
                    <div class="text-sm">Bank Asal: <span class="font-semibold">{{ $trx->origin_bank ?? '-' }}</span></div>
                    <div class="text-sm">Bank Tujuan: <span class="font-semibold">{{ $trx->destination_bank ?? '-' }}</span></div>
                    <div class="text-sm">Nominal Transfer: <span class="font-semibold">Rp {{ number_format((int)$trx->transfer_amount,0,',','.') }}</span></div>
                </div>
                <div class="glass p-4 rounded">
                    <div class="text-white/80 mb-2">Catatan</div>
                    <div class="text-sm">{{ $trx->transfer_note ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="glass p-6 rounded">Tidak ada transaksi pending.</div>
@endforelse
</div>
@endsection
