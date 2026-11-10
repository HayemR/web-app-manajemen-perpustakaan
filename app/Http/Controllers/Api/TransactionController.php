<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{
    /**
     * Submit a new book borrowing request
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request from the Vue.js frontend
        $validated = $request->validate([
            'user_id' => 'required|uuid',
            'book_id' => 'required|uuid',
        ]);

        // 2. Check if the requested book exists and is available
        $book = Book::find($validated['book_id']);

        if (!$book) {
            return response()->json(['message' => 'Book not found.'], 404);
        }

        // Checking the stock/availability flag based on your schema
        if ($book->stock <= 0 || !$book->is_available) {
            return response()->json(['message' => 'This book is currently out of stock.'], 400);
        }

        // 3. Create the transaction record
        // The UUID v7 primary key is generated automatically by the model
        $transaction = Transaction::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'status' => 'pending', 
            'request_date' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Borrowing request submitted successfully. Awaiting librarian approval.',
            'data' => $transaction
        ], 201);
    }
}