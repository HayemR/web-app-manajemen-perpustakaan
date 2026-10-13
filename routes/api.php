<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - DigiNesh Library Management
|--------------------------------------------------------------------------
*/

// Users (Shared foundation & actor selection)
Route::apiResource('users', UserController::class)->only(['index', 'store', 'show']);

// Modul Categories
Route::apiResource('categories', CategoryController::class);

// Modul Books
Route::get('books/lookup-isbn', [BookController::class, 'lookupIsbn'])->name('books.lookup-isbn');
Route::apiResource('books', BookController::class);
Route::post('books/{book}', [BookController::class, 'update'])->name('books.update.post'); // support multipart form data

// Modul Transactions (Logika Inti Peminjaman)
Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
Route::post('transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
Route::post('transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
Route::post('transactions/{transaction}/return', [TransactionController::class, 'returnBook'])->name('transactions.return');
