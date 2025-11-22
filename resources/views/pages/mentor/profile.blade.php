@extends('components.layout.mentor')

@section('content')
<div class="space-y-8">
  <h2 class="text-2xl font-semibold flex items-center gap-3">Profil Mentor
    @php
      $status = $currentStatus ?? 'pending';
      $statusClass = $status === 'approved' ? 'bg-green-500/20 text-green-300' : ($status === 'rejected' ? 'bg-red-500/20 text-red-300' : 'bg-yellow-500/20 text-yellow-300');
      $statusLabel = ucfirst($status);
    @endphp
    <span class="px-2 py-1 rounded text-xs {{ $statusClass }}">Status: {{ $statusLabel }}</span>
  </h2>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Form Identitas -->
    <div class="glass p-6 rounded-lg">
      <h3 class="text-xl font-semibold mb-4">Identitas</h3>
      <form method="POST" action="{{ route('mentor.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <x-ui.crud.input label="Nama" name="name" :value="$user->name" required variant="glass" />
        <x-ui.crud.input label="Email" name="email" :value="$user->email" variant="glass" disabled />
        <x-ui.crud.textarea label="Bio" name="bio" variant="glass">{{ old('bio', $user->bio) }}</x-ui.crud.textarea>

        <div class="space-y-2">
          <label class="block text-sm text-gray-300">Foto Profil (avatar)</label>
          <div class="flex items-center gap-4">
            <img src="{{ $user->avatar_url ?? 'https://placehold.co/64x64' }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover">
            <x-ui.crud.input name="avatar" type="file" variant="glass" accept="image/*" />
          </div>
        </div>

        <div class="flex gap-3">
          <x-ui.btn-primary type="submit" icon="fa-solid fa-save">Simpan</x-ui.btn-primary>
          <x-ui.btn-secondary href="{{ route('mentor.dashboard') }}">Batal</x-ui.btn-secondary>
        </div>
      </form>
    </div>

    <!-- Upload Dokumen -->
    <div class="glass p-6 rounded-lg">
      <h3 class="text-xl font-semibold mb-4">Dokumen Portofolio & CV</h3>
      <form id="bulkDocForm" method="POST" action="{{ route('mentor.profile.documents.bulk') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input id="cvFile" type="file" name="cv_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp" class="hidden" />
        <x-ui.crud.input id="cvFilename" label="Unggah CV" name="cv_filename" variant="glass" placeholder="Pilih file CV" readonly onclick="document.getElementById('cvFile').click()" />

        <input id="portfolioFile" type="file" name="portfolio_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp" class="hidden" />
        <x-ui.crud.input id="portfolioFilename" label="Unggah Portofolio" name="portfolio_filename" variant="glass" placeholder="Pilih file Portofolio" readonly onclick="document.getElementById('portfolioFile').click()" />
        <div class="flex gap-3">
          <x-ui.btn-primary type="submit" icon="fa-solid fa-save">{{ ($hasDocs ?? false) ? 'Simpan Ulang' : 'Simpan' }}</x-ui.btn-primary>
        </div>
      </form>

      <script>
      (function(){
        var cvInput = document.getElementById('cvFile');
        var cvName = document.getElementById('cvFilename');
        var pfInput = document.getElementById('portfolioFile');
        var pfName = document.getElementById('portfolioFilename');
        if(cvInput && cvName){
          cvInput.addEventListener('change', function(){
            if(cvInput.files && cvInput.files.length){ cvName.value = cvInput.files[0].name; }
          });
        }
        if(pfInput && pfName){
          pfInput.addEventListener('change', function(){
            if(pfInput.files && pfInput.files.length){ pfName.value = pfInput.files[0].name; }
          });
        }
      })();
      </script>

      

      

      <div class="mt-6">
        <h4 class="text-lg font-semibold mb-3">Dokumen Terkirim</h4>
        <div class="space-y-3">
          @forelse($verifications as $doc)
            <div class="flex items-center justify-between p-3 bg-white/5 rounded">
              <div>
                <div class="font-medium">{{ $doc->notes ?? 'Dokumen' }}</div>
                <div class="text-sm text-white/70">Status: {{ ucfirst($doc->status) }}</div>
                <div class="text-sm text-white/70">Waktu Upload: {{ $doc->created_at ? $doc->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') : '-' }} WIB</div>
                <div class="text-sm text-white/70">Waktu Disetujui: {{ ($doc->status === 'approved' && $doc->updated_at) ? $doc->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') : 'Menunggu' }} @if($doc->status === 'approved') WIB @endif</div>
              </div>
              <div class="flex gap-2">
                <a href="{{ $doc->document_url }}" target="_blank" class="text-yellow-300 underline">Lihat</a>
              </div>
            </div>
          @empty
            <div class="p-3 bg-white/5 rounded">Belum ada dokumen yang diunggah.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

</div>
@endsection