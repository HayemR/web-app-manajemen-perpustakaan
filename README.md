# Perancangan Sistem Inventaris dan Peminjaman Perpustakaan Web

Ini adalah repositori untuk sistem manajemen perpustakaan berbasis web. Proyek ini dibangun menggunakan **Laravel** sebagai *backend* (API & Logika Bisnis) dan **Vue.js + Vite** sebagai *frontend* (Antarmuka Interaktif).

## 🛠️ Prasyarat Lingkungan (Prerequisites)
Sebelum menjalankan proyek ini, pastikan komputermu sudah terinstal:
- PHP (Minimal v8.2)
- Composer
- Node.js & NPM
- Git
- MySQL / MariaDB (XAMPP/Laragon/sejenisnya)

---

## 🚀 Panduan Instalasi (Setup Awal untuk Tim)

Ikuti langkah-langkah berikut secara berurutan setelah kamu diundang ke repositori ini:

**1. Clone Repositori**
Buka terminal dan *clone* *branch* `develop` (pusat pengembangan tim):
```bash
git clone -b develop git@github.com:HayemR/web-app-manajemen-perpustakaan.git
cd web-app-manajemen-perpustakaan
```

**2. Instalasi Dependensi Backend (Laravel)**
Jalankan Composer untuk mengunduh semua *library* PHP yang dibutuhkan:
```bash
composer install
```

**3. Instalasi Dependensi Frontend (Vue & Vite)**
Jalankan NPM untuk mengunduh semua *library* JavaScript/Vue:
```bash
npm install
```

**4. Konfigurasi Environment (.env)**
File konfigurasi rahasia tidak ikut ter- *upload* ke Git. Kamu harus membuatnya sendiri dari file contoh yang disediakan:
```bash
cp .env.example .env
```
*Buka file `.env` di *code editor* kamu, lalu ubah bagian koneksi database sesuaikan dengan komputermu (buat *database* kosong bernama `perpus_db` di phpMyAdmin/DBeaver terlebih dahulu):*
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpus_db
DB_USERNAME=root
DB_PASSWORD=
```

**5. Generate App Key & Migrasi Database**
Buat kunci keamanan Laravel dan jalankan migrasi untuk membuat tabel-tabel di *database*:
```bash
php artisan key:generate
php artisan migrate
```

---

## 💻 Cara Menjalankan Proyek (Development)

Karena kita menggunakan Laravel dan Vite secara bersamaan, kamu **wajib membuka 2 terminal** dan menjalankan kedua perintah ini secara bersamaan:

**Terminal 1 (Backend - Server PHP):**
```bash
php artisan serve
```
*(Aplikasi bisa diakses di `http://127.0.0.1:8000`)*

**Terminal 2 (Frontend - Kompilasi Aset Vite):**
```bash
npm run dev
```
*(Biarkan terminal ini menyala. Ia akan otomatis me-refresh browser setiap kali kamu menyimpan file `.vue` atau `.js`)*

---

## 🌿 Aturan Git Flow (Penting!)
- **JANGAN** pernah melakukan `git push` langsung ke *branch* `main` atau `develop`.
- Selalu buat *branch* baru dari `develop` untuk setiap fitur yang kamu kerjakan.
```bash
# Contoh membuat branch untuk fitur login
git checkout -b feature/auth-user
```