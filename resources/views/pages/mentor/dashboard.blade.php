@extends('components.layout.mentor')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-semibold">Dashboard Mentor</h2>
            <p class="text-white/70 mt-1">Selamat datang kembali, {{ Auth::user()->name }}</p>
        </div>
        <div class="flex gap-3"></div>
    </div>

    <!-- Section: Keseluruhan -->
    <div class="space-y-4">
        <h3 class="text-xl font-semibold">Keseluruhan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-ui.stat-card label="Total Revenue" icon="fa-solid fa-wallet" value="Rp {{ number_format($stats['overall']['total_revenue'], 0, ',', '.') }}" />
            <x-ui.stat-card label="Total Komisi" icon="fa-solid fa-hand-holding-dollar" value="Rp {{ number_format($stats['overall']['total_commission'], 0, ',', '.') }}" />
            <x-ui.stat-card label="Total Siswa" icon="fa-solid fa-users" value="{{ $stats['overall']['total_students'] }}" />
        </div>
    </div>

    <!-- Section: Kursus -->
    <div class="space-y-4">
        <h3 class="text-xl font-semibold">Kursus</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-ui.stat-card label="Total Penjualan Kursus" icon="fa-solid fa-cart-shopping" value="{{ $stats['course']['sold_count'] }}" />
            <x-ui.stat-card label="Total Penghasilan Kursus" icon="fa-solid fa-sack-dollar" value="Rp {{ number_format($stats['course']['earnings'], 2, ',', '.') }}" />
            <x-ui.stat-card label="Total Komisi Kursus" icon="fa-solid fa-percent" value="Rp {{ number_format($stats['course']['commission'], 2, ',', '.') }}" />
        </div>
    </div>

    <!-- Section: Ebook -->
    <div class="space-y-4">
        <h3 class="text-xl font-semibold">Ebook</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-ui.stat-card label="Total Penjualan Ebook" icon="fa-solid fa-book" value="{{ $stats['ebook']['sold_count'] }}" />
            <x-ui.stat-card label="Total Penghasilan Ebook" icon="fa-solid fa-sack-dollar" value="Rp {{ number_format($stats['ebook']['earnings'], 2, ',', '.') }}" />
            <x-ui.stat-card label="Total Komisi Ebook" icon="fa-solid fa-percent" value="Rp {{ number_format($stats['ebook']['commission'], 2, ',', '.') }}" />
        </div>
    </div>

    <!-- Content Status Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Course Status -->
        <div class="glass p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chalkboard text-yellow-400"></i>
                Status Kursus
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-file-alt text-gray-400"></i>
                        Draft
                    </span>
                    <span class="font-semibold">{{ $stats['course_stats']['draft'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-check-circle text-green-400"></i>
                        Dipublikasikan
                    </span>
                    <span class="font-semibold">{{ $stats['course_stats']['published'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-archive text-orange-400"></i>
                        Diarsipkan
                    </span>
                    <span class="font-semibold">{{ $stats['course_stats']['archived'] }}</span>
                </div>
            </div>
        </div>

        <!-- Ebook Status -->
        <div class="glass p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <i class="fa-solid fa-book text-yellow-400"></i>
                Status E-book
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-file-alt text-gray-400"></i>
                        Draft
                    </span>
                    <span class="font-semibold">{{ $stats['ebook_stats']['draft'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-check-circle text-green-400"></i>
                        Dipublikasikan
                    </span>
                    <span class="font-semibold">{{ $stats['ebook_stats']['published'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-archive text-orange-400"></i>
                        Diarsipkan
                    </span>
                    <span class="font-semibold">{{ $stats['ebook_stats']['archived'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Courses -->
    @if($popularCourses->count() > 0)
    <div class="glass p-6 rounded-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold flex items-center gap-2">
                <i class="fa-solid fa-fire text-yellow-400"></i>
                Kursus Populer
            </h3>
            <x-ui.btn-secondary href="#" icon="fa-solid fa-eye">Lihat Semua</x-ui.btn-secondary>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4">Judul Kursus</th>
                        <th class="text-center py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4">Siswa</th>
                        <th class="text-right py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($popularCourses as $course)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/60x40' }}" alt="{{ $course->title }}" class="w-12 h-8 rounded object-cover">
                                <div>
                                    <div class="font-medium">{{ $course->title }}</div>
                                    <div class="text-sm text-white/70">{{ $course->category->name ?? 'Tidak ada kategori' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center py-3 px-4">
                            @if($course->status == 'published')
                                <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full">Aktif</span>
                            @elseif($course->status == 'draft')
                                <span class="px-2 py-1 bg-gray-500/20 text-gray-300 text-xs rounded-full">Draft</span>
                            @else
                                <span class="px-2 py-1 bg-orange-500/20 text-orange-300 text-xs rounded-full">Arsip</span>
                            @endif
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-semibold">{{ $course->enrollments_count }}</span>
                        </td>
                        <td class="text-right py-3 px-4">
                            <div class="flex gap-2 justify-end">
                                <x-ui.btn-secondary href="#" size="sm" icon="fa-solid fa-eye">Lihat</x-ui.btn-secondary>
                                <x-ui.btn-secondary href="#" size="sm" icon="fa-solid fa-edit">Edit</x-ui.btn-secondary>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Transactions -->
    @if($recentTransactions->count() > 0)
    <div class="glass p-6 rounded-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold flex items-center gap-2">
                <i class="fa-solid fa-receipt text-yellow-400"></i>
                Transaksi Terbaru
            </h3>
            <x-ui.btn-secondary href="#" icon="fa-solid fa-eye">Lihat Semua</x-ui.btn-secondary>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4">Produk</th>
                        <th class="text-left py-3 px-4">Pembeli</th>
                        <th class="text-center py-3 px-4">Harga</th>
                        <th class="text-center py-3 px-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $transaction)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                @if($transaction->product_type == 'course')
                                    <div>
                                        <div class="font-medium">{{ $transaction->course->title ?? 'Kursus' }}</div>
                                        <div class="text-sm text-white/70">Kursus</div>
                                    </div>
                                @else
                                    <div>
                                        <div class="font-medium">{{ $transaction->ebook->title ?? 'E-book' }}</div>
                                        <div class="text-sm text-white/70">E-book</div>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium">{{ $transaction->transaction->user->name }}</div>
                            <div class="text-sm text-white/70">{{ $transaction->transaction->user->email }}</div>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-semibold">Rp {{ number_format($transaction->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm">{{ $transaction->transaction->transaction_time->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Quiz Attempts -->
    @if($recentQuizAttempts->count() > 0)
    <div class="glass p-6 rounded-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold flex items-center gap-2">
                <i class="fa-solid fa-trophy text-yellow-400"></i>
                Aktivitas Kuis Terbaru
            </h3>
            <x-ui.btn-secondary href="#" icon="fa-solid fa-eye">Lihat Semua</x-ui.btn-secondary>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4">Siswa</th>
                        <th class="text-left py-3 px-4">Kuis</th>
                        <th class="text-center py-3 px-4">Skor</th>
                        <th class="text-center py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentQuizAttempts as $attempt)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $attempt->user->avatar_url ?? 'https://placehold.co/32x32' }}" alt="{{ $attempt->user->name }}" class="w-8 h-8 rounded-full">
                                <div>
                                    <div class="font-medium">{{ $attempt->user->name }}</div>
                                    <div class="text-sm text-white/70">{{ $attempt->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium">{{ $attempt->quiz->title }}</div>
                            <div class="text-sm text-white/70">{{ $attempt->quiz->lesson->module->course->title }}</div>
                        </td>
                        <td class="text-center py-3 px-4">
                            @if($attempt->score !== null)
                                <span class="font-semibold">{{ number_format($attempt->score, 1) }}</span>
                            @else
                                <span class="text-gray-400">Belum dinilai</span>
                            @endif
                        </td>
                        <td class="text-center py-3 px-4">
                            @if($attempt->completed_at)
                                <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full">Selesai</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-300 text-xs rounded-full">Dalam Proses</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection