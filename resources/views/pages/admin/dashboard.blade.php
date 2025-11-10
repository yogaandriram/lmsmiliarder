@extends('components.layout.admin')

@section('content')
<h2 class="text-3xl font-semibold mb-6">Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="glass p-6 rounded">
        <div class="text-sm text-yellow-300">Total Pengguna</div>
        <div class="text-4xl font-bold">{{ $stats['users'] }}</div>
    </div>
    <div class="glass p-6 rounded">
        <div class="text-sm text-yellow-300">Total Kursus</div>
        <div class="text-4xl font-bold">{{ $stats['courses'] }}</div>
    </div>
    <div class="glass p-6 rounded">
        <div class="text-sm text-yellow-300">Total E-book</div>
        <div class="text-4xl font-bold">{{ $stats['ebooks'] }}</div>
    </div>
    <div class="glass p-6 rounded">
        <div class="text-sm text-yellow-300">Verifikasi Mentor Pending</div>
        <div class="text-4xl font-bold">{{ $stats['mentor_pending'] }}</div>
    </div>
    <div class="glass p-6 rounded">
        <div class="text-sm text-yellow-300">Transaksi Pending</div>
        <div class="text-4xl font-bold">{{ $stats['transactions_pending'] }}</div>
    </div>
</div>
@endsection