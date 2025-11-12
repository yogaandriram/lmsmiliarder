@extends('components.layout.admin')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Rekening Bank Admin</h2>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="glass p-6 rounded">
        <h3 class="text-xl mb-4">Tambah Rekening</h3>
        <form method="POST" action="{{ route('admin.admin-bank-accounts.store') }}">
            @csrf
            <div class="mb-3">
    <x-ui.crud.input variant="glass" label="Nama Bank" name="bank_name" type="text" value="{{ old('bank_name') }}" required />
            </div>
            <div class="mb-3">
    <x-ui.crud.input variant="glass" label="Nomor Rekening" name="account_number" type="text" value="{{ old('account_number') }}" required />
            </div>
            <div class="mb-3">
    <x-ui.crud.input variant="glass" label="Nama Pemilik" name="account_name" type="text" value="{{ old('account_name') }}" required />
            </div>
            <div class="mb-3">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" class="mr-2"> Aktif
                </label>
            </div>
        <x-ui.btn-primary type="submit">Simpan</x-ui.btn-primary>
        </form>
    </div>

    <div class="glass p-6 rounded">
        <h3 class="text-xl mb-4">Daftar Rekening</h3>
        <table class="w-full text-left">
            <thead>
                <tr><th class="py-2">Bank</th><th class="py-2">No. Rekening</th><th class="py-2">Pemilik</th><th class="py-2">Aktif</th><th class="py-2">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($accounts as $acc)
                <tr class="border-t border-white/10">
                    <td class="py-2">{{ $acc->bank_name }}</td>
                    <td class="py-2">{{ $acc->account_number }}</td>
                    <td class="py-2">{{ $acc->account_name }}</td>
                    <td class="py-2">{{ $acc->is_active ? 'Ya' : 'Tidak' }}</td>
                    <td class="py-2">
                        <form method="POST" action="{{ route('admin.admin-bank-accounts.destroy', $acc) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="ml-2 text-red-400" onclick="return confirm('Hapus rekening?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-3 text-white/70">Belum ada rekening.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection