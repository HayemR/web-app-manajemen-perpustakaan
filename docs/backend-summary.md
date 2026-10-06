# Rangkuman Pengerjaan Backend API Perpustakaan (DigiNesh)

Dokumen ini merangkum seluruh pekerjaan implementasi backend API perpustakaan (DigiNesh) yang telah diselesaikan berdasarkan **Entity Relationship Diagram (ERD)** dan **Scope of Work (SoW)**.

---

## 1. Fondasi Arsitektur & Lingkungan Bersama (Shared Foundation)

### a. Primary Key UUID & Soft Deletes
*   **UUID sebagai Primary Key**: Seluruh tabel menggunakan tipe UUID (`uuid('id')->primary()`) dan seluruh Model Eloquent mengimplementasikan trait `Illuminate\Database\Eloquent\Concerns\HasUuids`.
*   **Soft Deletes**: Semua tabel dilengkapi kolom `deleted_at` (`$table->softDeletes()`) dan Model Eloquent mengimplementasikan trait `Illuminate\Database\Eloquent\SoftDeletes`.

### b. Skema Database yang Diimplementasikan
1.  **`users`**
    *   Kolom: `id` (uuid, PK), `name` (varchar), `nis` (varchar, nullable, unique), `email` (varchar, unique), `password` (varchar), `role` (varchar, default: `student`), `rfid_uid` (varchar, nullable, unique), `class_name` (varchar, nullable), `remember_token`, `timestamps`, `deleted_at`.
2.  **`attendances`**
    *   Kolom: `id` (uuid, PK), `user_id` (uuid, nullable, FK ke `users.id`), `guest_name` (varchar, nullable), `guest_institution` (varchar, nullable), `purpose` (varchar, nullable), `visitor_type` (varchar, default: `member`), `type` (varchar, default: `check_in`), `scan_time` (timestamp), `timestamps`, `deleted_at`.
3.  **`categories`**
    *   Kolom: `id` (uuid, PK), `name` (varchar), `timestamps`, `deleted_at`.
4.  **`books`**
    *   Kolom: `id` (uuid, PK), `category_id` (uuid, FK ke `categories.id`), `isbn_barcode` (varchar, nullable, unique), `title` (varchar), `author` (varchar), `publisher` (varchar), `published_year` (varchar), `stock` (integer, default: 0), `cover_image_url` (text, nullable), `description` (text, nullable), `timestamps`, `deleted_at`.
5.  **`transactions`**
    *   Kolom: `id` (uuid, PK), `user_id` (uuid, FK ke `users.id`), `book_id` (uuid, FK ke `books.id`), `approved_by` (uuid, nullable, FK ke `users.id`), `status` (varchar: `pending`, `active`, `returned`, `rejected`, `overdue`), `request_date` (timestamp), `borrow_date` (timestamp, nullable), `due_date` (timestamp, nullable), `return_date` (timestamp, nullable), `fine_amount` (decimal: 10,2, default: 0), `timestamps`, `deleted_at`.

### c. Relasi Eloquent
*   `Category` `hasMany` `Book`
*   `Book` `belongsTo` `Category` dan `hasMany` `Transaction`
*   `User` `hasMany` `Transaction` (relasi peminjam `transactions()` dan relasi persetujuan petugas `approvedTransactions()`)
*   `User` `hasMany` `Attendance`
*   `Attendance` `belongsTo` `User`
*   `Transaction` `belongsTo` `User` (`user`), `belongsTo` `User` (`approver`), dan `belongsTo` `Book` (`book`)

---

## 2. Modul Categories (Master Data Kategori)

Implementasi berada di `App\Http\Controllers\Api\CategoryController`.

*   **Fitur CRUD**:
    *   Daftar kategori dengan jumlah buku (`withCount('books')`) dan fitur pencarian kata kunci nama kategori.
    *   Dukungan pagination maupun pengambilan seluruh data (`?all=1`).
    *   Validasi pembuatan dan pembaruan: `name` wajib diisi string (maks 255 karakter).
*   **Constraint Proteksi Penghapusan**:
    *   Kategori **tidak dapat dihapus** jika masih memiliki relasi aktif ke data buku di tabel `books`.
    *   Mengembalikan respon error HTTP 422 (*Unprocessable Entity*) disertai pesan: *"Kategori tidak dapat dihapus karena masih ada buku yang berelasi dengan kategori ini."*

---

## 3. Modul Books (Inventaris Buku & Integrasi API Eksternal)

Implementasi berada di `App\Http\Controllers\Api\BookController` dan `App\Services\BookMetadataService`.

*   **Integrasi API Eksternal (Google Books API & Open Library API)**:
    *   Endpoint: `GET /api/books/lookup-isbn?isbn={isbn_barcode}`
    *   Sistem mencari metadata ke Google Books API terlebih dahulu (`https://www.googleapis.com/books/v1/volumes?q=isbn:...`).
    *   Jika tidak ditemukan atau terjadi kendala jaringan, otomatis melakukan fallback ke Open Library API (`https://openlibrary.org/api/books?bibkeys=ISBN:...`).
    *   Metadata yang diekstrak secara otomatis: `title`, `author`, `publisher`, `published_year`, `description`, dan `cover_image_url`.
*   **Manajemen Gambar Sampul (Cover Image)**:
    *   Mendukung URL gambar langsung dari API eksternal via kolom `cover_image_url`.
    *   Mendukung upload manual file gambar (`cover_image`) via `multipart/form-data`. File disimpan di disk storage `public/books` dan URL aset otomatis digenerate.
*   **Manajemen & Validasi Stok**:
    *   Kolom `stock` (integer, min: 0).
    *   Buku hanya dapat diajukan peminjamannya jika `stock > 0`.
*   **Proteksi Hapus Buku**:
    *   Buku dicegah dihapus jika masih terkait transaksi peminjaman yang sedang aktif atau pending.

---

## 4. Modul Transactions (Logika Inti Peminjaman & Pengembalian)

Implementasi berada di `App\Http\Controllers\Api\TransactionController` dan `App\Console\Commands\CheckOverdueLoans`.

### a. Alur Multi-Aktor & State Management
Siklus status transaksi:
`pending` -> `active` -> `returned` / `rejected` / `overdue`

1.  **Pengajuan Siswa (`POST /api/transactions`)**:
    *   Parameter: `user_id`, `book_id`.
    *   Validasi ketersediaan stok: Jika `book.stock <= 0`, ditolak (HTTP 422: *"Buku ini tidak dapat dipinjam karena stok saat ini habis."*).
    *   Validasi duplikasi: Mencegah siswa mengajukan buku yang sama jika masih memiliki status `pending`, `active`, atau `overdue` untuk buku tersebut.
    *   Sistem otomatis menetapkan `status = pending`, `request_date = now()`, dan `fine_amount = 0`.
2.  **Persetujuan Petugas (`POST /api/transactions/{id}/approve`)**:
    *   Parameter: `approved_by` (UUID petugas), `loan_days` (opsional, default: 7 hari).
    *   Validasi status: Hanya transaksi berstatus `pending` yang dapat disetujui.
    *   **Integritas Stok & Concurrency**: Menggunakan `DB::transaction` dan `lockForUpdate()` pada tabel buku.
    *   **Pengurangan Stok**: Stok buku otomatis dikurangi 1 (`$book->decrement('stock')`).
    *   Pembaruan status: `status = active`, `borrow_date = now()`, dan `due_date = now() + loan_days`.
3.  **Penolakan Petugas (`POST /api/transactions/{id}/reject`)**:
    *   Parameter: `approved_by` (UUID petugas), `reason` (opsional).
    *   Hanya transaksi `pending` yang dapat ditolak. `status` berubah menjadi `rejected`.
4.  **Pengembalian Buku (`POST /api/transactions/{id}/return`)**:
    *   Validasi status: Hanya transaksi `active` atau `overdue` yang dapat diproses pengembaliannya.
    *   **Penambahan Stok**: Stok buku otomatis dikembalikan/ditambah 1 (`$book->increment('stock')`).
    *   Pencatatan waktu: `return_date = now()`, `status = returned`.
    *   **Kalkulasi Denda (Fines)**:
        *   Jika `return_date > due_date`, dihitung selisih hari keterlambatan berdasarkan kalender:
            $$\text{fine\_amount} = \text{hari\_terlambat} \times \text{tarif\_denda\_per\_hari}$$
        *   Tarif denda default: Rp 1.000 / hari (dapat dikonfigurasi melalui `config/library.php` atau `.env`).
        *   Jika tepat waktu, denda bernilai `0.00`.

### b. Otomasi Deteksi Jatuh Tempo (Overdue Scheduler)
*   **Artisan Command**: `php artisan library:check-overdue`
    *   Mencari semua transaksi `active` yang telah melewati `due_date`.
    *   Mengubah status menjadi `overdue` dan mengupdate nominal denda berjalan.
*   **Scheduler**: Telah didaftarkan pada `routes/console.php` untuk berjalan setiap hari (`daily()`).

---

## 5. Ringkasan Route API

| Method | Endpoint | Fungsi |
| :--- | :--- | :--- |
| `GET` | `/api/categories` | Mengambil daftar kategori (filter `search`, `all`) |
| `POST` | `/api/categories` | Membuat kategori baru |
| `GET` | `/api/categories/{id}` | Mengambil detail kategori & daftar buku terkait |
| `PUT/PATCH` | `/api/categories/{id}` | Memperbarui nama kategori |
| `DELETE` | `/api/categories/{id}` | Menghapus kategori (dilindungi relasi buku) |
| `GET` | `/api/books` | Mengambil daftar buku (filter `category_id`, `search`, `in_stock`) |
| `GET` | `/api/books/lookup-isbn` | Lookup metadata buku via ISBN ke Google Books & Open Library |
| `POST` | `/api/books` | Menambah data buku baru (support cover file / URL) |
| `GET` | `/api/books/{id}` | Mengambil detail buku |
| `PUT/PATCH` | `/api/books/{id}` | Memperbarui data buku |
| `POST` | `/api/books/{id}` | Memperbarui data buku (alternatif `multipart/form-data`) |
| `DELETE` | `/api/books/{id}` | Menghapus buku (dilindungi status peminjaman aktif) |
| `GET` | `/api/transactions` | Mengambil daftar transaksi (filter `status`, `user_id`, `book_id`, `search`) |
| `POST` | `/api/transactions` | Pengajuan peminjaman oleh siswa (`status = pending`) |
| `GET` | `/api/transactions/{id}` | Mengambil detail transaksi & estimasi denda berjalan |
| `POST` | `/api/transactions/{id}/approve` | Persetujuan oleh petugas (stok -1, set `due_date`) |
| `POST` | `/api/transactions/{id}/reject` | Penolakan oleh petugas (`status = rejected`) |
| `POST` | `/api/transactions/{id}/return` | Pengembalian fisik buku oleh petugas (stok +1, hitung denda) |
| `GET` | `/api/users` | Mengambil daftar pengguna untuk pilihan peminjam/petugas |
| `POST` | `/api/users` | Menambahkan pengguna baru (siswa/petugas) |
| `GET` | `/api/users/{id}` | Mengambil detail pengguna |

---

## 6. Daftar File yang Dibuat & Dimodifikasi

### File Konfigurasi & Routing
*   `bootstrap/app.php`: Mendaftarkan rute `api: routes/api.php`.
*   `routes/api.php`: Definisi rute seluruh endpoint API perpustakaan.
*   `routes/console.php`: Pendaftaran scheduler harian `library:check-overdue`.
*   `config/library.php`: Konfigurasi durasi pinjam default (7 hari) dan tarif denda (Rp 1.000/hari).

### Migration & Database
*   `database/migrations/0001_01_01_000000_create_users_table.php`
*   `database/migrations/2026_10_06_000001_create_attendances_table.php`
*   `database/migrations/2026_10_06_000002_create_categories_table.php`
*   `database/migrations/2026_10_06_000003_create_books_table.php`
*   `database/migrations/2026_10_06_000004_create_transactions_table.php`
*   `database/seeders/DatabaseSeeder.php`
*   `database/factories/UserFactory.php`
*   `database/factories/CategoryFactory.php`
*   `database/factories/BookFactory.php`
*   `database/factories/TransactionFactory.php`

### Model & Service
*   `app/Models/User.php`
*   `app/Models/Attendance.php`
*   `app/Models/Category.php`
*   `app/Models/Book.php`
*   `app/Models/Transaction.php`
*   `app/Services/BookMetadataService.php`

### Controller & Console
*   `app/Http/Controllers/Api/CategoryController.php`
*   `app/Http/Controllers/Api/BookController.php`
*   `app/Http/Controllers/Api/TransactionController.php`
*   `app/Http/Controllers/Api/UserController.php`
*   `app/Console/Commands/CheckOverdueLoans.php`

### Automated Feature Tests
*   `tests/Feature/CategoryApiTest.php`
*   `tests/Feature/BookApiTest.php`
*   `tests/Feature/TransactionApiTest.php`

---

## 7. Hasil Pengujian & Seeding Data

Semua logika bisnis telah divalidasi melalui rangkaian pengujian otomatis PHPUnit/Laravel Test:
```bash
php artisan test
```
**Hasil Test**:
```text
PASS  Tests\Feature\CategoryApiTest (7 tests)
PASS  Tests\Feature\BookApiTest (5 tests)
PASS  Tests\Feature\TransactionApiTest (8 tests)
PASS  Tests\Feature\ExampleTest (1 test)
PASS  Tests\Unit\ExampleTest (2 tests)

Tests:    23 passed (69 assertions)
Duration: 0.62s
```

Database telah di-seed dengan akun petugas (`petugas@diginesh.sch.id`), akun siswa, kategori buku, koleksi buku nyata dengan cover dan barcode ISBN, serta contoh transaksi pada berbagai status (`pending`, `active`, `overdue`, dan `returned`).
