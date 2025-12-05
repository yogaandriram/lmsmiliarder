@extends('components.layout.mentor')
@section('page_title','Pengaturan')

@section('content')
@php
  $tab = request('tab') ?? 'accounts';
  $isTab = function($t) use($tab){ return $tab === $t; };
  $accounts = $accounts ?? [];
@endphp

<div class="space-y-6">
  <h2 class="text-2xl font-semibold">Settings</h2>

  <div class="glass p-4 rounded">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <aside class="lg:col-span-3">
        <div class="space-y-1">
          <a href="{{ route('mentor.settings', ['tab' => 'accounts']) }}" class="block px-3 py-2 rounded {{ $isTab('accounts') ? 'bg-white/20' : 'bg-white/5' }} text-white">Rekening</a>
          <a href="{{ route('mentor.settings', ['tab' => 'profile']) }}" class="block px-3 py-2 rounded {{ $isTab('profile') ? 'bg-white/20' : 'bg-white/5' }} text-white">Profil</a>
          <a href="{{ route('mentor.settings', ['tab' => 'integrations']) }}" class="block px-3 py-2 rounded {{ $isTab('integrations') ? 'bg-white/20' : 'bg-white/5' }} text-white">Integrations</a>
        </div>
      </aside>

      <section class="lg:col-span-9">
        @if($isTab('accounts'))
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
              <h3 class="text-xl font-semibold">Rekening</h3>
              <button class="text-yellow-300" onclick="toggleModal('mentorModalAddAccount')">+ Tambah Rekening</button>
            </div>
            <div class="w-64">
              <x-ui.search name="search_accounts" placeholder="Cari rekening..." />
            </div>
          </div>

          @if($accounts === [] || count($accounts) === 0)
            <div class="p-6 rounded bg-white/5 text-white/70">Belum ada rekening. Tambahkan rekening bank untuk menerima pembayaran.</div>
          @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              @foreach($accounts as $acc)
                <div class="p-4 rounded bg-white/5 relative">
                  <div class="absolute top-2 right-2">
                    @if($acc->is_default)
                      <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-300 text-black" title="Rekening default">
                        <i class="fa-solid fa-star"></i>
                      </span>
                    @else
                      <form method="POST" action="{{ route('mentor.mentor-bank-accounts.default', $acc) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/10 text-white/80 hover:bg-white/20" title="Jadikan default">
                          <i class="fa-regular fa-star"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                  <div class="flex items-center gap-5">
                    <div class="w-24 h-24 rounded bg-white/10 flex items-center justify-center text-white font-bold text-2xl">{{ strtoupper(substr($acc->bank_name,0,2)) }}</div>
                    <div class="flex-1">
                      <div class="font-semibold uppercase text-lg">{{ $acc->bank_name }}</div>
                      <div class="text-white/70 text-sm">{{ $acc->account_number }}</div>
                      <div class="text-white/50 text-xs">{{ $acc->account_holder_name }} • {{ $acc->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                      <div class="mt-4 flex items-center gap-2">
                        <x-ui.btn-secondary size="sm" onclick="toggleModal('mentorModalEdit{{ $acc->id }}')">Edit</x-ui.btn-secondary>
                        <form method="POST" action="{{ route('mentor.mentor-bank-accounts.destroy', $acc) }}">
                          @csrf
                          @method('DELETE')
                          <x-ui.btn-primary type="submit" size="sm" variant="danger" icon="fa-solid fa-trash">Hapus</x-ui.btn-primary>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="mentorModalEdit{{ $acc->id }}" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
                  <div class="glass p-6 rounded w-full max-w-md">
                    <h4 class="text-lg font-semibold mb-4">Edit Rekening</h4>
                    <form method="POST" action="{{ route('mentor.mentor-bank-accounts.update', $acc) }}" class="space-y-3">
                      @csrf
                      @method('PUT')
                      <x-ui.crud.input label="Nama Bank" name="bank_name" :value="$acc->bank_name" required variant="glass" />
                      <x-ui.crud.input label="Nomor Rekening" name="account_number" :value="$acc->account_number" required variant="glass" />
                      <x-ui.crud.input label="Atas Nama" name="account_holder_name" :value="$acc->account_holder_name" required variant="glass" />
                      <label class="inline-flex items-center gap-2 text-white/80">
                        <input type="checkbox" name="is_active" value="1" @checked($acc->is_active)>
                        <span>Aktif</span>
                      </label>
                      <label class="inline-flex items-center gap-2 text-white/80">
                        <input type="checkbox" name="is_default" value="1" @checked($acc->is_default)>
                        <span>Jadikan Default</span>
                      </label>
                      <div class="flex gap-2 justify-end">
                        <x-ui.btn-secondary type="button" onclick="toggleModal('mentorModalEdit{{ $acc->id }}')">Batal</x-ui.btn-secondary>
                        <x-ui.btn-primary type="submit">Simpan</x-ui.btn-primary>
                      </div>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        @elseif($isTab('profile'))
          <div class="space-y-4">
            <h3 class="text-xl font-semibold">Profil Mentor</h3>
            <div class="glass p-4 rounded">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.crud.input label="Nama" name="name" variant="glass" />
                <x-ui.crud.input label="Email" name="email" type="email" variant="glass" />
                <x-ui.crud.input label="Nomor Telepon" name="phone" variant="glass" />
                <x-ui.crud.input label="Website/Portofolio" name="website" variant="glass" />
                <div class="md:col-span-2">
                  <x-ui.crud.textarea label="Deskripsi" name="bio" rows="4" variant="glass" />
                </div>
              </div>
              <div class="mt-4 text-right">
                <x-ui.btn-primary type="button">Simpan Perubahan</x-ui.btn-primary>
              </div>
            </div>
          </div>
        @else
          <div class="text-white/70">Integrations akan tersedia di versi berikutnya.</div>
        @endif
      </section>
    </div>
  </div>
</div>

<!-- Modal Tambah Rekening Mentor -->
<div id="mentorModalAddAccount" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
  <div class="glass p-6 rounded w-full max-w-md">
    <h4 class="text-lg font-semibold mb-4">Tambah Rekening</h4>
    <form method="POST" action="{{ route('mentor.mentor-bank-accounts.store') }}" class="space-y-3">
      @csrf
      <x-ui.crud.input label="Nama Bank" name="bank_name" required variant="glass" />
      <x-ui.crud.input label="Nomor Rekening" name="account_number" required variant="glass" />
      <x-ui.crud.input label="Atas Nama" name="account_holder_name" required variant="glass" />
      <label class="inline-flex items-center gap-2 text-white/80">
        <input type="checkbox" name="is_active" value="1">
        <span>Aktif</span>
      </label>
      <label class="inline-flex items-center gap-2 text-white/80">
        <input type="checkbox" name="is_default" value="1">
        <span>Jadikan Default</span>
      </label>
      <div class="flex gap-2 justify-end">
        <x-ui.btn-secondary type="button" onclick="toggleModal('mentorModalAddAccount')">Batal</x-ui.btn-secondary>
        <x-ui.btn-primary type="submit">Simpan</x-ui.btn-primary>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  if(window.__mentor_modal_loaded) return; 
  window.__mentor_modal_loaded = true;
  window.toggleModal = function(id){
    var el = document.getElementById(id);
    if(!el) return;
    var willShow = el.classList.contains('hidden');
    el.classList.toggle('hidden');
    if(willShow){
      el.classList.add('grid','place-items-center');
      document.body.classList.add('overflow-hidden');
    } else {
      el.classList.remove('grid','place-items-center');
      document.body.classList.remove('overflow-hidden');
    }
  }
})();
</script>
@endsection
