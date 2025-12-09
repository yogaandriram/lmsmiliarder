@extends('components.layout.mentor')
@section('page_title','Analitik')

@section('content')
<div class="space-y-8">
  <div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Analitik</h2>
  </div>

  <x-ui.date-range-dropdown :action="route('mentor.analytics.index')" :startValue="$start" :endValue="$end" />

  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <x-ui.stat-card label="Total Penjualan" :value="'Rp '.number_format($totalSales,0,',','.')" icon="fa-solid fa-money-bill-wave" />
    <x-ui.stat-card label="Total Order" :value="$totalOrders" icon="fa-solid fa-receipt" />
    <x-ui.stat-card label="Kursus Terjual" :value="$coursesSold" icon="fa-solid fa-chalkboard" />
    <x-ui.stat-card label="E-book Terjual" :value="$ebooksSold" icon="fa-solid fa-book" />
  </div>

  <div class="glass p-6 rounded">
    <h3 class="text-lg font-semibold text-yellow-400 mb-4">Penjualan Harian</h3>
    <x-ui.chart-barline :labels="$labels" :bars="$bars" :line="$line" :height="280" />
  </div>
</div>
@endsection
