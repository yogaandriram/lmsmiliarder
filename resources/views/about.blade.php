@extends('components.layout.app')

@section('page_title', 'Tentang Kami')

@section('content')
<div class="max-w-4xl mx-auto space-y-12">
    <!-- Hero Section -->
    <div class="text-center space-y-4">
        <h1 class="text-4xl font-bold text-white">Tentang EduLux</h1>
        <p class="text-lg text-white/60 max-w-2xl mx-auto">
            Platform pembelajaran digital premium yang menghubungkan Anda dengan mentor ahli untuk menguasai skill masa depan.
        </p>
    </div>

    <!-- Vision & Mission -->
    <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-2xl hover:bg-white/10 transition-colors duration-300">
            <div class="w-12 h-12 rounded-xl bg-yellow-500/10 flex items-center justify-center mb-6">
                <i class="fa-solid fa-eye text-2xl text-yellow-500"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-4">Visi Kami</h2>
            <p class="text-white/60 leading-relaxed">
                Menjadi katalis utama dalam demokratisasi pendidikan berkualitas tinggi di Indonesia, mencetak talenta digital yang siap bersaing di kancah global.
            </p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-2xl hover:bg-white/10 transition-colors duration-300">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center mb-6">
                <i class="fa-solid fa-rocket text-2xl text-purple-500"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-4">Misi Kami</h2>
            <ul class="space-y-3 text-white/60">
                <li class="flex gap-3">
                    <i class="fa-solid fa-check text-green-400 mt-1"></i>
                    <span>Menyediakan akses ke materi pembelajaran standar industri.</span>
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-check text-green-400 mt-1"></i>
                    <span>Memberdayakan mentor ahli untuk berbagi pengetahuan.</span>
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-check text-green-400 mt-1"></i>
                    <span>Membangun ekosistem belajar yang interaktif dan suportif.</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center p-6 bg-white/5 backdrop-blur-lg rounded-2xl border border-white/10 shadow-lg hover:bg-white/10 transition-all duration-300 hover:scale-105">
            <div class="text-3xl font-bold text-white mb-2">10K+</div>
            <div class="text-sm text-white/50">Siswa Aktif</div>
        </div>
        <div class="text-center p-6 bg-white/5 rounded-2xl border border-white/10">
            <div class="text-3xl font-bold text-white mb-2">500+</div>
            <div class="text-sm text-white/50">Kursus Premium</div>
        </div>
        <div class="text-center p-6 bg-white/5 rounded-2xl border border-white/10">
            <div class="text-3xl font-bold text-white mb-2">100+</div>
            <div class="text-sm text-white/50">Mentor Ahli</div>
        </div>
        <div class="text-center p-6 bg-white/5 rounded-2xl border border-white/10">
            <div class="text-3xl font-bold text-white mb-2">4.9</div>
            <div class="text-sm text-white/50">Rating Rata-rata</div>
        </div>
    </div>
</div>
@endsection
