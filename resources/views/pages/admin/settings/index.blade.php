@extends('components.layout.admin')
@section('page_title', 'Pengaturan')

@section('content')
<div class="space-y-6">
  <h2 class="text-2xl font-semibold">Settings</h2>

  <div class="glass p-4 rounded">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Sidebar Kiri -->
      <aside class="lg:col-span-3">
        <div class="space-y-1">
          @php $isTab = function($t) use($tab){ return ($tab ?? 'accounts') === $t; }; @endphp
          <a href="{{ route('admin.settings.index', ['tab' => 'accounts']) }}" class="block px-3 py-2 rounded {{ $isTab('accounts') ? 'bg-white/20' : 'bg-white/5' }} text-white">Rekening</a>
          <a href="{{ route('admin.settings.index', ['tab' => 'coupons']) }}" class="block px-3 py-2 rounded {{ $isTab('coupons') ? 'bg-white/20' : 'bg-white/5' }} text-white">Kupon Diskon</a>
          <a href="{{ route('admin.settings.index', ['tab' => 'integrations']) }}" class="block px-3 py-2 rounded {{ $isTab('integrations') ? 'bg-white/20' : 'bg-white/5' }} text-white">Integrations</a>
        </div>
      </aside>

      <!-- Konten Kanan -->
      <section class="lg:col-span-9">
        @if(($tab ?? 'accounts') === 'accounts')
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
              <h3 class="text-xl font-semibold">Rekening</h3>
              <button class="text-yellow-300" onclick="toggleModal('modalAddAccount')">+ Tambah Rekening</button>
            </div>
            <div class="w-64">
              <x-ui.search name="search_accounts" placeholder="Cari rekening..." />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($accounts as $acc)
              <div class="p-4 rounded bg-white/5">
                <div class="flex items-center gap-5">
                  <div class="w-24 h-24 rounded bg-white/10 flex items-center justify-center text-white font-bold text-2xl">{{ strtoupper(substr($acc->bank_name,0,2)) }}</div>
                  <div class="flex-1">
                    <div class="font-semibold uppercase text-lg">{{ $acc->bank_name }}</div>
                    <div class="text-white/70 text-sm">{{ $acc->account_number }}</div>
                    <div class="text-white/50 text-xs">{{ $acc->account_holder_name }} • {{ $acc->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                    <div class="mt-4 flex items-center gap-2">
                      <x-ui.btn-secondary size="sm" onclick="toggleModal('modalDetail{{ $acc->id }}')">Detail</x-ui.btn-secondary>
                      <x-ui.btn-secondary size="sm" onclick="toggleModal('modalEdit{{ $acc->id }}')">Edit</x-ui.btn-secondary>
                      <form method="POST" action="{{ route('admin.admin-bank-accounts.destroy', $acc) }}">
                        @csrf
                        @method('DELETE')
                        <x-ui.btn-primary type="submit" size="sm" variant="danger" icon="fa-solid fa-trash">Hapus</x-ui.btn-primary>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Modal didefinisikan global di bawah -->
            @endforeach
          </div>
        @elseif(($tab ?? 'accounts') === 'coupons')
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
              <h3 class="text-xl font-semibold">Kupon Diskon</h3>
              <button class="text-yellow-300" onclick="toggleModal('modalAddCoupon')">+ Tambah Kupon</button>
            </div>
            <div class="w-64">
              <x-ui.search name="search_coupons" placeholder="Cari kode kupon..." />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($coupons as $c)
              <div class="p-4 rounded bg-white/5">
                <div class="flex items-start gap-5">
                  <div class="w-24 h-24 rounded bg-white/10 flex items-center justify-center text-white font-bold text-xl">{{ strtoupper(substr($c->code,0,2)) }}</div>
                  <div class="flex-1">
                    <div class="font-semibold text-lg">{{ $c->code }}</div>
                    <div class="text-white/70 text-sm">Tipe: {{ $c->discount_type === 'percentage' ? 'Persentase' : 'Nominal' }} • Nilai: {{ $c->discount_type === 'percentage' ? ($c->discount_value.'%') : ('Rp '.number_format($c->discount_value,0,',','.')) }}</div>
                    <div class="text-white/60 text-xs">Kedaluwarsa: {{ \Illuminate\Support\Carbon::parse($c->expires_at)->format('d M Y H:i') }} • {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                    <div class="mt-4 flex items-center gap-2">
                      <x-ui.btn-secondary size="sm" onclick="toggleModal('modalCouponEdit{{ $c->id }}')">Edit</x-ui.btn-secondary>
                      <form method="POST" action="{{ route('admin.coupons.destroy', $c) }}">
                        @csrf
                        @method('DELETE')
                        <x-ui.btn-primary type="submit" size="sm" variant="danger" icon="fa-solid fa-trash">Hapus</x-ui.btn-primary>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-white/70">Integrations akan tersedia di versi berikutnya.</div>
        @endif
      </section>
    </div>
  </div>
</div>

@if(($tab ?? 'accounts') === 'accounts')
@foreach($accounts as $acc)
  <!-- Global Modal Detail -->
  <div id="modalDetail{{ $acc->id }}" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
    <div class="glass p-6 rounded w-full max-w-md">
      <h4 class="text-lg font-semibold mb-4">Detail Rekening</h4>
      <div class="space-y-2 text-white/80">
        <div>Bank: {{ $acc->bank_name }}</div>
        <div>Nomor: {{ $acc->account_number }}</div>
        <div>Atas Nama: {{ $acc->account_holder_name }}</div>
        <div>Status: {{ $acc->is_active ? 'Aktif' : 'Nonaktif' }}</div>
      </div>
      <div class="mt-4 text-right">
        <x-ui.btn-secondary onclick="toggleModal('modalDetail{{ $acc->id }}')">Tutup</x-ui.btn-secondary>
      </div>
    </div>
  </div>

  <!-- Global Modal Edit -->
  <div id="modalEdit{{ $acc->id }}" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
    <div class="glass p-6 rounded w-full max-w-md">
      <h4 class="text-lg font-semibold mb-4">Edit Rekening</h4>
      <form method="POST" action="{{ route('admin.admin-bank-accounts.update', $acc) }}" class="space-y-3">
        @csrf
        @method('PUT')
        <x-ui.crud.input label="Nama Bank" name="bank_name" :value="$acc->bank_name" required variant="glass" />
        <x-ui.crud.input label="Nomor Rekening" name="account_number" :value="$acc->account_number" required variant="glass" />
        <x-ui.crud.input label="Atas Nama" name="account_holder_name" :value="$acc->account_holder_name" required variant="glass" />
        <label class="inline-flex items-center gap-2 text-white/80">
          <input type="checkbox" name="is_active" value="1" @checked($acc->is_active)>
          <span>Aktif</span>
        </label>
        <div class="flex gap-2 justify-end">
          <x-ui.btn-secondary type="button" onclick="toggleModal('modalEdit{{ $acc->id }}')">Batal</x-ui.btn-secondary>
          <x-ui.btn-primary type="submit">Simpan</x-ui.btn-primary>
        </div>
      </form>
    </div>
  </div>
@endforeach
@endif

<!-- Global Modal Tambah -->
@if(($tab ?? 'accounts') === 'accounts')
<div id="modalAddAccount" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
  <div class="glass p-6 rounded w-full max-w-md">
    <h4 class="text-lg font-semibold mb-4">Tambah Rekening</h4>
    <form method="POST" action="{{ route('admin.admin-bank-accounts.store') }}" class="space-y-3">
      @csrf
      <x-ui.crud.input label="Nama Bank" name="bank_name" required variant="glass" />
      <x-ui.crud.input label="Nomor Rekening" name="account_number" required variant="glass" />
      <x-ui.crud.input label="Atas Nama" name="account_holder_name" required variant="glass" />
      <label class="inline-flex items-center gap-2 text-white/80">
        <input type="checkbox" name="is_active" value="1">
        <span>Aktif</span>
      </label>
      <div class="flex gap-2 justify-end">
        <x-ui.btn-secondary type="button" onclick="toggleModal('modalAddAccount')">Batal</x-ui.btn-secondary>
        <x-ui.btn-primary type="submit">Simpan</x-ui.btn-primary>
      </div>
    </form>
  </div>
</div>
@endif

@if(($tab ?? 'accounts') === 'coupons')
<!-- Modal Tambah Kupon -->
<div id="modalAddCoupon" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
  <div class="glass p-6 rounded w-full max-w-md">
    <h4 class="text-lg font-semibold mb-4">Tambah Kupon</h4>
    <form method="POST" action="{{ route('admin.coupons.store') }}" class="space-y-3">
      @csrf
      <x-ui.crud.input label="Kode Kupon" name="code" required variant="glass" />
      <label class="block text-sm font-medium text-white/90 mb-2">Tipe Diskon</label>
      <select name="discount_type" class="w-full p-2 bg-white/10 border border-white/20 rounded">
        <option value="percentage">Persentase (%)</option>
        <option value="fixed">Nominal (Rp)</option>
      </select>
      <x-ui.crud.input label="Nilai Diskon" name="discount_value" type="number" step="0.01" required variant="glass" />
      <x-ui.crud.input label="Kedaluwarsa" name="expires_at" type="datetime-local" required variant="glass" />
      <x-ui.crud.input label="Batas Penggunaan" name="usage_limit" type="number" variant="glass" />
      <label class="inline-flex items-center gap-2 text-white/80">
        <input type="checkbox" name="is_active" value="1">
        <span>Aktif</span>
      </label>
      <div class="flex gap-2 justify-end">
        <x-ui.btn-secondary type="button" onclick="toggleModal('modalAddCoupon')">Batal</x-ui.btn-secondary>
        <x-ui.btn-primary type="submit">Simpan</x-ui.btn-primary>
      </div>
    </form>
  </div>
</div>

<!-- Modals Edit Kupon -->
@foreach($coupons as $c)
  <div id="modalCouponEdit{{ $c->id }}" class="fixed inset-0 w-screen h-screen bg-black/60 z-50 hidden">
    <div class="glass p-6 rounded w-full max-w-md">
      <h4 class="text-lg font-semibold mb-4">Edit Kupon</h4>
      <form method="POST" action="{{ route('admin.coupons.update', $c) }}" class="space-y-3">
        @csrf
        @method('PUT')
        <x-ui.crud.input label="Kode Kupon" name="code" :value="$c->code" required variant="glass" />
        <label class="block text-sm font-medium text-white/90 mb-2">Tipe Diskon</label>
        <select name="discount_type" class="w-full p-2 bg-white/10 border border-white/20 rounded">
          <option value="percentage" @selected($c->discount_type==='percentage')>Persentase (%)</option>
          <option value="fixed" @selected($c->discount_type==='fixed')>Nominal (Rp)</option>
        </select>
        <x-ui.crud.input label="Nilai Diskon" name="discount_value" type="number" step="0.01" :value="$c->discount_value" required variant="glass" />
        <x-ui.crud.input label="Kedaluwarsa" name="expires_at" type="datetime-local" :value="\Illuminate\Support\Carbon::parse($c->expires_at)->format('Y-m-d\\TH:i')" required variant="glass" />
        <x-ui.crud.input label="Batas Penggunaan" name="usage_limit" type="number" :value="$c->usage_limit" variant="glass" />
        <label class="inline-flex items-center gap-2 text-white/80">
          <input type="checkbox" name="is_active" value="1" @checked($c->is_active)>
          <span>Aktif</span>
        </label>
        <div class="flex gap-2 justify-end">
          <x-ui.btn-secondary type="button" onclick="toggleModal('modalCouponEdit{{ $c->id }}')">Batal</x-ui.btn-secondary>
          <x-ui.btn-primary type="submit">Simpan</x-ui.btn-primary>
        </div>
      </form>
    </div>
  </div>
@endforeach
@endif

<script>
(function(){
  if(window.__edulux_modal_loaded) return; 
  window.__edulux_modal_loaded = true;
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