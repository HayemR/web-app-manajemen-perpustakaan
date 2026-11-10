<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\AttendanceController;

/*
|--------------------------------------------------------------------------
| Public Routes (No login required)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

// Hardware integrations shouldn't require student login, just hardware validation
Route::post('/attendances', [AttendanceController::class, 'store']); 
Route::get('/books/scan/{isbn}', [BookController::class, 'showByIsbn']);
Route::get('/books', [BookController::class, 'index']); // Anyone can browse the catalog

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Sanctum API Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // User Session
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });

    // Student Borrowing Transactions
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/users/{user_id}/transactions', [TransactionController::class, 'userTransactions']);
    
    // Librarians returning a book (you might want to add further role checks later)
    Route::put('/transactions/{id}/return', [TransactionController::class, 'returnBook']);
});