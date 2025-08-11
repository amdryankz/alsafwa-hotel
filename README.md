# Aplikasi Manajemen Hotel

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)

Aplikasi berbasis web yang dibangun dari awal menggunakan Laravel 12. Proyek ini mencakup alur kerja hotel yang komprehensif, mulai dari reservasi, manajemen tamu, transaksi, hingga pelaporan keuangan dan pengujian otomatis.

## Fitur Utama 🌟

Aplikasi ini memiliki serangkaian fitur yang dikelompokkan ke dalam beberapa modul:

#### Modul Transaksi & Reservasi

- ✅ Manajemen Reservasi untuk pemesanan di masa depan.
- ✅ Proses Check-in & Check-out tamu.
- ✅ Fitur Pindah Kamar untuk tamu yang sedang menginap.
- ✅ Pembatalan Reservasi.
- ✅ Dasbor Kalender interaktif untuk melihat hunian kamar.

#### Modul Keuangan & Billing

- 💰 Pencatatan pembayaran ganda dengan metode berbeda (Tunai, QRIS, dll).
- 🧾 Penambahan Diskon (nominal) dan PPN (persen) pada tagihan.
- ➕ Pencatatan layanan tambahan (laundry, room service).
- 📄 Cetak Struk & Invoice dengan format profesional.
- 💸 Manajemen CRUD untuk Pengeluaran Operasional.

#### Manajemen Data & Laporan

- 👥 Manajemen CRUD untuk Data Tamu dengan filter pencarian.
- 🛏️ Manajemen CRUD untuk Tipe Kamar dan Kamar.
- 📈 Laporan Keuangan (Pemasukan & Pengeluaran) dengan filter tanggal.
- 📊 Ekspor laporan ke format Excel dengan header dan total otomatis.

#### Keamanan & Administrasi

- 🛡️ Sistem otentikasi dan hak akses berbasis peran (Admin, Staff, Akuntan, Owner).
- 📝 Manajemen CRUD untuk Karyawan (Users).
- 📜 Log Aktivitas untuk melacak setiap perubahan data penting (Create, Update, Delete).

## Teknologi yang Digunakan 🛠️

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Pengujian**: Pest (Feature & Unit Testing)
- **Library Tambahan**:
  - `spatie/laravel-activitylog` untuk Log Aktivitas
  - `maatwebsite/excel` untuk ekspor data
  - `Tom Select` untuk dropdown pencarian
  - `FullCalendar` untuk dasbor kalender

## Instalasi & Setup Lokal 🚀

Untuk menjalankan proyek ini di lingkungan lokal, ikuti langkah-langkah berikut:

1. **Clone repositori ini:**

   ```bash
   git clone https://github.com/amd_ryankz/management-hotel-app.git
   cd management-hotel-app
   ```
2. **Install dependensi PHP:**

   ```bash
   composer install
   ```
3. **Setup file `.env`:**

   ```bash
   cp .env.example .env
   ```

   Setelah itu, buka file `.env` dan konfigurasikan koneksi database Anda.
4. **Generate application key:**

   ```bash
   php artisan key:generate
   ```
5. **Jalankan migrasi dan seeder database:**
   *Pastikan Anda sudah membuat database kosong sesuai konfigurasi di `.env`.*

   ```bash
   php artisan migrate --seed
   ```
6. **Install dependensi JavaScript & compile aset:**

   ```bash
   npm install
   npm run dev
   ```
7. **Jalankan server pengembangan:**

   ```bash
   php artisan serve
   ```

   Aplikasi akan tersedia di `http://127.0.0.1:8000`.

## Akun Demo 🔑

Anda bisa login menggunakan akun-akun berikut yang dibuat oleh `UserSeeder`:

- **Admin**: `admin@hotel.com`
- **Owner**: `owner@hotel.com`
- **Akuntan**: `akuntan@hotel.com`
- **Front Office**: `fo@hotel.com`
- **Password** untuk semua akun: `password`

## Lisensi 📄

Proyek ini berada di bawah **MIT License**. Lihat file `LICENSE` untuk detail lebih lanjut.
