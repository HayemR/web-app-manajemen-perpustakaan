# DigiNesh - Ganesha Library

**Kode Proyek:** EST-2026[cite: 4]
**Jenis Proyek:** Pengembangan Sistem Informasi Perpustakaan (SIP)[cite: 4]

DigiNesh adalah sistem informasi perpustakaan digital yang memungkinkan petugas mendata buku dengan mudah melalui pemindaian ISBN, serta memungkinkan pemustaka (siswa) mengajukan peminjaman secara mandiri[cite: 4]. Sistem ini dirancang dengan arsitektur *frontend* dan *backend* yang terpisah[cite: 4].

## 🚀 Baseline Teknologi
*   **Backend:** Laravel 13 + PHP 8.4[cite: 4]
*   **Customer Frontend:** Vue.js 3 + TypeScript + Tailwind CSS v4[cite: 4]
*   **Admin Panel:** Laravel Livewire 4 + Tailwind CSS v4[cite: 4]
*   **Database:** MySQL 8.4[cite: 4]
*   **Primary Key:** UUID v7[cite: 4]

## ✨ Fitur Utama (Fase MVP)
1. **Otomatisasi Pendataan Buku:** Penambahan data buku baru dengan memindai *barcode* ISBN fisik bawaan penerbit menggunakan kamera/scanner[cite: 6].
2. **Auto-Fill Data:** Penarikan data metadata buku otomatis via Google Books API / Open Library API berdasarkan ISBN yang dipindai[cite: 6].
3. **Pengajuan Mandiri (Self-Service):** Akses katalog publik dan pengajuan peminjaman (status *pending*) secara mandiri oleh siswa tanpa menulis di kertas[cite: 4, 6].
4. **Verifikasi Petugas:** Panel administrasi bagi petugas untuk menyetujui (*approve*) peminjaman dan memproses pengembalian buku[cite: 4, 6].
5. **Integrasi Sistem:** Kesiapan tabel `users` dengan kolom `rfid_uid` untuk integrasi dengan sistem presensi perangkat keras (kartu RFID) milik tim perangkat keras[cite: 5].

## 🛠️ Panduan Instalasi (Untuk Anggota Tim)

Pastikan **PHP 8.4**, **Composer**, **Node.js**, dan **MySQL 8.4** sudah terinstal di komputer.

1. Clone Repositori
```bash
git clone https://github.com/HayemR/web-app-manajemen-perpustakaan
cd web-app-manajemen-perpustakaan
```

2. Instalasi Depedensi
```bash
# Backend
composer install

# Frontend
npm install
```

3. Konfigurasi Environment
```bash
cp .env.example .env
```

4. Sesuaikan .env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpus_db
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migrasi

```bash
php artisan migrate:fresh
```
