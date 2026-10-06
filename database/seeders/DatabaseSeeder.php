<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Librarian, Admin, Students)
        $librarian = User::create([
            'name' => 'Siti Nurhaliza (Petugas)',
            'nis' => '1001',
            'email' => 'petugas@diginesh.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'librarian',
            'rfid_uid' => 'RFID-LIBRARIAN-01',
            'class_name' => null,
        ]);

        $student1 = User::create([
            'name' => 'Ahmad Fauzi',
            'nis' => '20241001',
            'email' => 'ahmad.fauzi@diginesh.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'rfid_uid' => 'E28011700000020',
            'class_name' => 'XI PPLG 1',
        ]);

        $student2 = User::create([
            'name' => 'Budi Santoso',
            'nis' => '20241002',
            'email' => 'budi.santoso@diginesh.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'rfid_uid' => 'E28011700000021',
            'class_name' => 'XI PPLG 2',
        ]);

        $student3 = User::create([
            'name' => 'Citra Lestari',
            'nis' => '20241003',
            'email' => 'citra.lestari@diginesh.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'rfid_uid' => 'E28011700000022',
            'class_name' => 'X TJKT 1',
        ]);

        // 2. Seed Categories
        $catRPL = Category::create(['name' => 'Rekayasa Perangkat Lunak']);
        $catJaringan = Category::create(['name' => 'Jaringan & Keamanan Siber']);
        $catSains = Category::create(['name' => 'Sains & Matematika']);
        $catSastra = Category::create(['name' => 'Sastra & Fiksi']);
        $catPengembangan = Category::create(['name' => 'Pengembangan Diri']);

        // 3. Seed Books
        $bookCleanCode = Book::create([
            'category_id' => $catRPL->id,
            'isbn_barcode' => '9780132350884',
            'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
            'author' => 'Robert C. Martin',
            'publisher' => 'Prentice Hall',
            'published_year' => '2008',
            'stock' => 5,
            'cover_image_url' => 'https://m.media-amazon.com/images/I/41xShlnTZTL._SX376_BO1,204,203,200_.jpg',
            'description' => 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees.',
        ]);

        $bookPragmatic = Book::create([
            'category_id' => $catRPL->id,
            'isbn_barcode' => '9780135957059',
            'title' => 'The Pragmatic Programmer: Your Journey To Mastery',
            'author' => 'David Thomas, Andrew Hunt',
            'publisher' => 'Addison-Wesley Professional',
            'published_year' => '2019',
            'stock' => 3,
            'cover_image_url' => 'https://m.media-amazon.com/images/I/51W1sBPO7tL._SX380_BO1,204,203,200_.jpg',
            'description' => 'A classic handbook for modern software engineers covering pragmatic philosophy and practices.',
        ]);

        $bookNetworking = Book::create([
            'category_id' => $catJaringan->id,
            'isbn_barcode' => '9780136658559',
            'title' => 'Computer Networks',
            'author' => 'Andrew S. Tanenbaum, David J. Wetherall',
            'publisher' => 'Pearson',
            'published_year' => '2021',
            'stock' => 4,
            'cover_image_url' => 'https://m.media-amazon.com/images/I/512yAaw4B1L._SX382_BO1,204,203,200_.jpg',
            'description' => 'Comprehensive introduction to computer networking principles, protocols, and architectures.',
        ]);

        $bookAtomic = Book::create([
            'category_id' => $catPengembangan->id,
            'isbn_barcode' => '9780735211292',
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'publisher' => 'Avery',
            'published_year' => '2018',
            'stock' => 2,
            'cover_image_url' => 'https://m.media-amazon.com/images/I/51-nXsSRfZL._SX328_BO1,204,203,200_.jpg',
            'description' => 'An easy & proven way to build good habits and break bad ones.',
        ]);

        $bookLaskar = Book::create([
            'category_id' => $catSastra->id,
            'isbn_barcode' => '9789793062792',
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'publisher' => 'Bentang Pustaka',
            'published_year' => '2005',
            'stock' => 1,
            'cover_image_url' => 'https://upload.wikimedia.org/wikipedia/id/8/8e/Laskar_pelangi_sampul.jpg',
            'description' => 'Novel inspiratif tentang kisah sepuluh laskar pelangi di Belitung.',
        ]);

        // 4. Seed Transactions in different states
        // A. Pending request from Student 1
        Transaction::create([
            'user_id' => $student1->id,
            'book_id' => $bookCleanCode->id,
            'approved_by' => null,
            'status' => Transaction::STATUS_PENDING,
            'request_date' => Carbon::now()->subHours(2),
            'borrow_date' => null,
            'due_date' => null,
            'return_date' => null,
            'fine_amount' => 0,
        ]);

        // B. Active loan by Student 2
        Transaction::create([
            'user_id' => $student2->id,
            'book_id' => $bookPragmatic->id,
            'approved_by' => $librarian->id,
            'status' => Transaction::STATUS_ACTIVE,
            'request_date' => Carbon::now()->subDays(2),
            'borrow_date' => Carbon::now()->subDays(2),
            'due_date' => Carbon::now()->addDays(5),
            'return_date' => null,
            'fine_amount' => 0,
        ]);

        // C. Overdue loan by Student 3 (borrowed 10 days ago, due 3 days ago -> 3 days late, fine = 3000)
        Transaction::create([
            'user_id' => $student3->id,
            'book_id' => $bookAtomic->id,
            'approved_by' => $librarian->id,
            'status' => Transaction::STATUS_OVERDUE,
            'request_date' => Carbon::now()->subDays(10),
            'borrow_date' => Carbon::now()->subDays(10),
            'due_date' => Carbon::now()->subDays(3),
            'return_date' => null,
            'fine_amount' => 3000,
        ]);

        // D. Returned transaction
        Transaction::create([
            'user_id' => $student1->id,
            'book_id' => $bookNetworking->id,
            'approved_by' => $librarian->id,
            'status' => Transaction::STATUS_RETURNED,
            'request_date' => Carbon::now()->subDays(15),
            'borrow_date' => Carbon::now()->subDays(14),
            'due_date' => Carbon::now()->subDays(7),
            'return_date' => Carbon::now()->subDays(7),
            'fine_amount' => 0,
        ]);
    }
}
