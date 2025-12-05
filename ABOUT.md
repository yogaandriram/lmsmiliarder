Persyaratan Proyek: Learning Management System (LMS) v2.0

Dokumen ini merinci persyaratan fungsional dan non-fungsional untuk platform LMS berdasarkan skema database yang telah disediakan.

1. Gambaran Umum Proyek

Proyek ini bertujuan untuk membangun sebuah platform E-Learning yang komprehensif. Sistem ini akan memungkinkan Admin untuk mengelola platform, Mentor untuk membuat dan menjual konten, dan Member untuk membeli dan mengonsumsi konten tersebut.

Platform ini akan mendukung dua jenis produk utama: Courses (kursus video/teks terstruktur) dan Ebooks (buku digital yang dapat diunduh). Sistem ini juga harus mencakup fungsionalitas untuk autentikasi pengguna, verifikasi mentor, pemrosesan transaksi (termasuk transfer manual), kuis, forum diskusi, dan pelacakan kemajuan belajar.

2. Peran Pengguna & Hak Akses (Roles)

Sistem akan memiliki tiga peran pengguna utama:

Member (Anggota):

Peran default saat registrasi.

Dapat menelusuri kursus dan e-book.

Dapat melakukan pembelian.

Dapat mengakses dan mengonsumsi konten yang telah dibeli.

Dapat berpartisipasi dalam grup diskusi kursus yang terdaftar.

Dapat mengerjakan kuis dan melihat hasil.

Dapat mengajukan diri untuk menjadi mentor.

Mentor (Pengajar):

Peran yang diperoleh setelah disetujui oleh Admin.

Memiliki semua hak Member.

Dapat membuat, mengedit, dan mengelola (draft, publish, archive) kursus dan e-book milik mereka sendiri.

Dapat membuat modul, materi (lesson), dan kuis di dalam kursus mereka.

Dapat memantau kemajuan siswa di kursus mereka (termasuk hasil kuis).

Bertindak sebagai moderator di grup diskusi kursus mereka (misal: menyematkan thread).

Admin (Administrator):

Memiliki hak akses penuh ke seluruh sistem.

Dapat mengelola semua pengguna (termasuk mengubah peran).

Mengelola proses verifikasi mentor (menyetujui/menolak pengajuan).

Mengelola kategori dan tag platform.

Mengelola semua konten (kursus dan e-book), terlepas dari siapa mentornya.

Mengelola transaksi (memverifikasi bukti bayar, mengubah status).

Mengelola kupon diskon.

Mengelola rekening bank admin.

Membuat pengumuman platform.

3. Persyaratan Fungsional (Functional Requirements)

F1: Manajemen Pengguna & Autentikasi

Registrasi: Pengguna baru dapat mendaftar dengan nama, email, dan kata sandi.

Login: Pengguna dapat login menggunakan email dan kata sandi.

Verifikasi Email: Sistem harus mengirimkan kode OTP (otp_verifications) ke email pengguna saat registrasi untuk verifikasi.

Reset Kata Sandi: Fungsionalitas "Lupa Kata Sandi" yang kemungkinan juga menggunakan sistem OTP.

Manajemen Profil: Pengguna dapat memperbarui profil mereka (nama, bio, avatar).

Verifikasi Mentor:

Seorang member dapat mengajukan diri menjadi mentor dengan mengunggah dokumen (mentor_verifications).

Admin memiliki dasbor untuk meninjau pengajuan ini (status: pending, approved, rejected) dan memberikan catatan.

Setelah disetujui (approved), peran pengguna terkait di tabel users harus diperbarui menjadi mentor.

F2: Manajemen Konten (Kursus & E-book)

Kategori & Tag: Admin dapat melakukan CRUD (Create, Read, Update, Delete) untuk categories dan tags.

Pembuatan Kursus (Mentor):

Mentor dapat membuat kursus baru (courses) dengan mengisi judul, deskripsi, thumbnail, harga, dan memilih 1 kategori.

Mentor dapat melampirkan banyak tags ke kursus.

Mentor dapat membuat modules (Bab) di dalam kursus dan mengaturnya berdasarkan order.

Mentor dapat membuat lessons (Materi) di dalam modul, berisi konten teks/HTML, URL video, estimasi durasi, dan order.

Pembuatan E-book (Mentor):

Mentor dapat membuat e-book baru (ebooks) dengan mengisi judul, deskripsi, gambar sampul, harga, dan mengunggah file e-book (file_url).

Manajemen Konten: Mentor dapat mengelola status konten mereka (course_status: draft, published, archived) untuk menyembunyikan atau menampilkannya di katalog.

F3: Sistem Transaksi & Pembayaran

Katalog Produk: Pengguna (termasuk tamu) dapat menelusuri kursus dan e-book yang berstatus published.

Checkout:

Sistem harus dapat memproses transaksi (transactions) yang berisi beberapa item (transaction_details).

Setiap item dalam transaction_details harus mencatat product_type (course/ebook) dan ID produk terkait (course_id atau ebook_id).

Kupon:

Admin dapat membuat kupon (coupons) dengan tipe diskon (percentage atau fixed), nilai, batas waktu, dan batas penggunaan.

Pengguna dapat memasukkan kode kupon saat checkout untuk mendapatkan diskon, yang akan dicatat di discount_amount.

Pembayaran Transfer Manual:

Saat checkout, pengguna dapat memilih salah satu rekening bank tujuan (admin_bank_accounts) yang aktif.

Transaksi dibuat dengan payment_status: pending.

Pengguna harus mengunggah bukti pembayaran (payment_proof_url).

Verifikasi Pembayaran (Admin):

Admin memiliki dasbor untuk meninjau transaksi pending yang memiliki payment_proof_url.

Admin dapat mengubah status pembayaran menjadi success atau failed.

F4: Manajemen Akses & Pembelajaran

Pemberian Akses (Otomatis): Ketika Admin mengubah payment_status transaksi menjadi success, sistem harus secara otomatis:

Untuk setiap transaction_details dengan product_type: course, buat entri baru di tabel enrollments.

Untuk setiap transaction_details dengan product_type: ebook, buat entri baru di tabel user_ebook_library.

Akses Konten:

Pengguna yang terdaftar di enrollments dapat mengakses halaman materi (lessons) dari kursus tersebut.

Pengguna yang ada di user_ebook_library dapat mengakses/mengunduh file_url dari e-book tersebut.

Pelacakan Kemajuan:

Di dalam halaman materi, pengguna harus dapat menandai materi sebagai "Selesai".

Tindakan ini akan membuat/memperbarui entri di learning_progress dengan completed_at yang diisi.

Sistem harus menampilkan ringkasan kemajuan (misal: "10 dari 15 materi selesai") di halaman kursus.

F5: Sistem Kuis & Evaluasi

Pembuatan Kuis (Mentor): Mentor dapat membuat kuis (quizzes) yang terkait dengan sebuah lesson. Kuis memiliki judul dan batas waktu.

Pembuatan Pertanyaan: Mentor dapat menambahkan pertanyaan (quiz_questions) ke kuis dengan tipe (multiple_choice atau essay) dan urutan.

Pilihan Ganda: Untuk pertanyaan multiple_choice, mentor dapat menambahkan beberapa quiz_options dan menandai satu sebagai is_correct.

Pengerjaan Kuis (Member):

Pengguna yang terdaftar dapat memulai pengerjaan kuis (user_quiz_attempts).

Sistem harus mencatat started_at dan memberlakukan time_limit_minutes.

Saat selesai, completed_at dicatat.

Penilaian:

Sistem harus dapat menilai jawaban pilihan ganda secara otomatis.

(Implisit) Sistem harus menyediakan antarmuka bagi mentor untuk menilai jawaban essay dan memasukkan score akhir ke user_quiz_attempts.

F6: Interaksi & Komunitas

Grup Diskusi: Setiap kursus (courses) harus memiliki satu grup diskusi (discussion_groups) yang terkait secara unik.

Forum (Thread & Reply):

Pengguna yang terdaftar di kursus dapat membuat discussion_threads (topik baru) di dalam grup diskusi.

Pengguna lain (termasuk mentor) dapat membalas (discussion_replies) topik tersebut.

Sistem harus mendukung balasan berantai/nested (menggunakan parent_reply_id).

Pengguna (termasuk mentor) dapat membalas (discussion_replies) topik tersebut.

Moderasi: Mentor atau Admin harus dapat menyematkan (is_pinned) thread penting.

Pengumuman: Admin dapat membuat announcements yang dapat dilihat oleh semua pengguna.

Notifikasi:

Sistem harus membuat notifications untuk pengguna (recipient_id) ketika:

Ada balasan baru di thread yang mereka ikuti.

Status verifikasi mentor mereka berubah.

Status transaksi mereka diperbarui (sukses/gagal).

Admin memposting pengumuman baru.

Pengguna harus memiliki area untuk melihat notifikasi dan menandainya sebagai read_at.

4. Persyaratan Non-Fungsional (Non-Functional Requirements)

Keamanan:

Semua kata sandi (password) harus di-hash menggunakan algoritma yang kuat.

Akses ke file yang diunggah (mentor document_url, ebook file_url, payment_proof_url) harus diamankan. File e-book tidak boleh dapat diakses publik tanpa autentikasi dan otorisasi (kepemilikan).

Otorisasi berbasis peran harus diterapkan secara ketat di seluruh API/endpoint.

Performa:

Waktu muat halaman katalog dan halaman materi (termasuk video) harus cepat.

Kueri database, terutama untuk forum diskusi (nested replies) dan pelacakan kemajuan, harus dioptimalkan.

Usability (Kegunaan):

Antarmuka pengguna (UI) harus bersih, intuitif, dan responsif (dapat diakses di perangkat seluler).

Alur checkout dan unggah bukti bayar harus jelas dan mudah bagi pengguna.

Skalabilitas: Arsitektur sistem harus dapat menangani pertumbuhan jumlah pengguna, kursus, dan data transaksi.

Visual Branding & UI/UX:

Desain Style: Sistem harus mengimplementasikan estetika desain Glassmorphism. Elemen UI harus terlihat transparan, buram, dan memiliki efek "kaca" dengan bayangan lembut dan batas yang jelas.

Warna Primer: Warna utama yang mendominasi adalah Hitam. Ini akan digunakan untuk latar belakang utama, header, footer, dan elemen penting lainnya.

Warna Sekunder:

Kuning: Digunakan sebagai warna aksen untuk tombol interaktif (CTA), highlight, ikon, indikator progres, dan elemen yang membutuhkan perhatian.

Putih: Digunakam untuk teks utama, latar belakang kartu (dengan efek glassmorphism), dan ikon yang membutuhkan kontras tinggi.

Tipografi: Pilihan font harus mendukung keterbacaan yang baik di atas efek glassmorphism, dengan kontras yang cukup dari warna teks (putih atau variasi kuning).

Ikonografi: Seluruh ikon yang digunakan dalam sistem harus bersumber dari library Font Awesome untuk menjaga konsistensi visual.

5.7 Model Bisnis Bagi Hasil Kursus

- Tujuan: Mendukung bagi hasil antara Mentor dan Admin per kursus dalam bentuk persentase.
- Skema Data:
  - Tambah kolom di `courses`:
    - `mentor_share_percent` int [not null, default: 80, range: 0..100]
    - `verified_at` sudah ada; tidak berubah
    - `intro_video_url` sudah ada; tidak berubah
  - `admin_share_percent` tidak disimpan; dihitung saat runtime: `100 - mentor_share_percent`.
- Aturan & Validasi:
  - `mentor_share_percent` wajib dalam rentang 0..100 (integer).
  - Jika `price = 0` (gratis), bagi hasil diabaikan: `mentor_earning = 0`, `admin_commission = 0`.
  - Nilai Admin selalu komplementer terhadap nilai Mentor dan total selalu 100.
- Perilaku UI (Mentor):
  - Di halaman Buat/Edit Kursus ditampilkan dua field:
    - "Untuk Mentor (%)" (editable)
    - "Untuk Admin (%)" (read-only atau editable tersinkron)
  - Saat Mentor mengisi nilai (mis. 70), field Admin otomatis menjadi 30.
  - Jika Admin diizinkan editable, perubahan salah satu field otomatis menyelaraskan field lain agar total = 100.
  - Tooltip pada label menjelaskan aturan komplementer dan rentang validasi.
- Perhitungan (Transaksi Sukses):
  - `effective_price` per item kursus = harga item setelah kupon/diskon diterapkan dan tidak negatif.
  - `mentor_earning` = `effective_price * mentor_share_percent / 100`.
  - `admin_commission` = `effective_price - mentor_earning`.
  - Dashboard Mentor/Admin menggunakan persentase per-kursus; jika tidak diisi, gunakan default 80%/20%.
- Dampak Kupon/Discount:
  - Bagi hasil dihitung dari `effective_price` setelah diskon/kupon pada item (bukan dari harga katalog jika berbeda).
- Migrasi:
  - Tambahkan migrasi: `add_mentor_share_percent_to_courses_table` menambah kolom `mentor_share_percent` (int, default 80, not null).
  - Sesuaikan model `Course` agar kolom baru dapat diisi (fillable) dan di-cast jika diperlukan.
- API/Server:
  - Endpoint create/update kursus menerima `mentor_share_percent` dan memvalidasi 0..100.
  - `admin_share_percent` dihitung di service layer; tidak perlu disimpan (kecuali untuk kebutuhan auditing).
- Auditing (Opsional):
- Sistem dapat menyimpan nilai perhitungan `mentor_earning` dan `admin_commission` per item transaksi untuk pelacakan laporan.

5.8 Sistem Komisi Penjualan (Kursus & E-book)

- Tujuan: Mengatur bagi hasil otomatis antara Mentor dan Admin setiap kali ada penjualan produk (kursus atau e-book). Mendukung pengaturan default di level platform, override di level mentor, serta pengaturan per produk.
- Skema Data:
  - Tambah kolom di `ebooks`:
    - `mentor_share_percent` int [not null, default: 80, range: 0..100]
  - Tambah opsi audit (opsional tapi direkomendasikan) di `transaction_details`:
    - `mentor_earning` decimal(12,2) [nullable]
    - `admin_commission` decimal(12,2) [nullable]
  - Default platform:
    - Admin dapat mengatur default persen komisi untuk Kursus dan E-book secara global (mis. Kursus: 80/20, E-book: 80/20). Default ini dipakai saat membuat konten baru dan saat kolom bagi hasil pada produk belum diisi.
- Aturan & Validasi:
  - Rentang persen 0..100. Total Admin + Mentor selalu 100.
  - Perhitungan komisi selalu dari `effective_price` item setelah kupon/diskon, tidak negatif.
  - Override prioritas: Per Produk > Per Mentor (opsional) > Default Platform.
- Perilaku UI
  - Settings Admin (pages.admin.settings.index):
    - Tab baru “Komisi” yang memuat:
      - Default komisi Kursus (% Mentor, otomatis % Admin).
      - Default komisi E-book (% Mentor, otomatis % Admin).
      - Opsional: Tabel override per mentor (jika diaktifkan), termasuk batas minimum/maksimum.
      - Penjelasan formula dan contoh hitung berdasarkan `effective_price`.
  - Settings Mentor (pages.mentor.settings):
    - Tab baru “Komisi” yang memuat:
      - Informasi default platform yang berlaku.
      - Form untuk menetapkan `mentor_share_percent` pada produk mentor (kursus/e-book) atau preferensi default pribadinya (opsional, tunduk pada batas admin).
      - Penjelasan dampak kupon terhadap komisi.
- Perhitungan (Transaksi Sukses):
  - `effective_price` per detail = harga item setelah diskon (min 0).
  - `mentor_earning` = `effective_price * mentor_share_percent / 100`.
  - `admin_commission` = `effective_price - mentor_earning`.
  - Nilai bisa disimpan di `transaction_details` untuk pelaporan; jika tidak disimpan, dihitung on-the-fly saat menampilkan laporan.
- Pelaporan & Pembayaran (Opsional Tahap Berikutnya):
  - Laporan pendapatan mentor dan komisi admin per periode.
  - Rekap per produk dan agregasi per mentor.
  - Mekanisme payout manual/otomatis berdasarkan saldo periode berjalan.
