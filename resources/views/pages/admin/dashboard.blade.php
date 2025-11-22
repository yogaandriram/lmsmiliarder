@extends('components.layout.admin')

@section('content')
<h2 class="text-3xl font-semibold mb-6">Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <x-ui.stat-card label="Total Pengguna" value="{{ $stats['users'] }}" icon="fa-solid fa-users" />
    <x-ui.stat-card label="Total Kursus" value="{{ $stats['courses'] }}" icon="fa-solid fa-chalkboard" />
    <x-ui.stat-card label="Total E-book" value="{{ $stats['ebooks'] }}" icon="fa-solid fa-book" />
    <x-ui.stat-card label="Verifikasi Mentor Pending" value="{{ $stats['mentor_pending'] }}" icon="fa-solid fa-id-card" />
    <x-ui.stat-card label="Transaksi Pending" value="{{ $stats['transactions_pending'] }}" icon="fa-solid fa-wallet" />
</div>
@endsection