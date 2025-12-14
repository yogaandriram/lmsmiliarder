<footer class="mt-16 pb-10 z-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl shadow-2xl backdrop-blur-xl
                    bg-linear-to-b from-white/80 via-white/40 to-white/60
                    dark:from-neutral-900/80 dark:via-neutral-900/40 dark:to-neutral-900/60
                    ring-1 ring-black/5 dark:ring-white/10 p-8 lg:p-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 lg:gap-8">
                <!-- Brand Column -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-graduation-cap text-4xl text-yellow-500"></i>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">EduLux LMS</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        Platform edukasi finansial terdepan yang membantu jutaan orang Indonesia mencapai kebebasan finansial melalui pembelajaran yang terstruktur dan praktis.
                    </p>
                    <div class="flex items-center gap-4">
                        @foreach(['instagram', 'tiktok', 'youtube', 'x-twitter', 'facebook'] as $social)
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 hover:bg-yellow-400 hover:text-white dark:hover:bg-yellow-500 transition-all duration-300">
                            <i class="fa-brands fa-{{ $social }} text-lg"></i>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Fast Nav -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Navigasi Cepat</h3>
                    <ul class="space-y-4">
                        @foreach(['Tentang Kami', 'Semua Kursus', 'Mentor', 'Komunitas', 'Berlangganan'] as $item)
                        <li>
                            <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors">
                                {{ $item }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Bantuan & Dukungan</h3>
                    <ul class="space-y-4">
                        @foreach(['FAQ', 'Pusat Bantuan', 'Hubungi Kami', 'Kebijakan Privasi', 'Syarat & Ketentuan'] as $item)
                        <li>
                            <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors">
                                {{ $item }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Kontak Kami</h3>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <i class="fa-solid fa-location-dot mt-1 text-yellow-500"></i>
                            <span class="text-gray-600 dark:text-gray-400">
                                Jl. Soekarno Hatta No. 123<br>
                                Kota Malang, 10220<br>
                                Indonesia
                            </span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fa-solid fa-phone text-yellow-500"></i>
                            <span class="text-gray-600 dark:text-gray-400">+62 21 1234 5678</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fa-solid fa-envelope text-yellow-500"></i>
                            <span class="text-gray-600 dark:text-gray-400">mailinfo@EduLux.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-gray-200 dark:via-white/10 to-transparent my-10"></div>

            <!-- Bottom Bar -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-500">
                <p>&copy; {{ date('Y') }} FinEdu. All rights reserved. Powered by Indonesia's Financial Education Revolution.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors">Terms Of Services</a>
                    <a href="#" class="hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors">Cookies Policy</a>
                </div>
            </div>

        </div>
    </div>
</footer>
