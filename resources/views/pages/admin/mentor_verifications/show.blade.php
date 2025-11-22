@extends('components.layout.admin')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Dokumen Mentor: {{ $user->name }} ({{ $user->email }})</h2>

<div class="glass p-6 rounded space-y-4">
  @forelse($documents as $doc)
    <div class="flex items-start justify-between p-4 bg-white/5 rounded">
      <div>
        <div class="font-medium">{{ $doc->notes ?? 'Dokumen' }}</div>
        <div class="text-sm text-white/70">Status: {{ ucfirst($doc->status) }}</div>
        <div class="text-sm text-white/70">Waktu Upload: {{ $doc->created_at ? $doc->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') : '-' }} WIB</div>
        <div class="text-sm text-white/70">Waktu Disetujui: {{ ($doc->status === 'approved' && $doc->updated_at) ? $doc->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') : 'Menunggu' }} @if($doc->status === 'approved') WIB @endif</div>
      </div>
      <div>
        <a href="{{ $doc->document_url }}" target="_blank" class="text-yellow-300 underline">Lihat</a>
      </div>
    </div>
  @empty
    <div class="p-4 bg-white/5 rounded">Tidak ada dokumen untuk mentor ini.</div>
  @endforelse

  <div class="flex gap-2">
    <x-ui.btn-secondary href="{{ route('admin.mentor_verifications.index') }}">Kembali</x-ui.btn-secondary>
  </div>
</div>
@endsection