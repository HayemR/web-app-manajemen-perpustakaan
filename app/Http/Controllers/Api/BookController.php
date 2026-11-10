<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Get a list of all available books (for the student catalog)
     */
    public function index(Request $request)
    {
        // Example: Filter by category if the frontend sends ?category_id=xyz
        $query = Book::query();
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->where('stock', '>', 0)->get();

        return response()->json([
            'success' => true,
            'data' => $books
        ], 200);
    }

    /**
     * Look up a book by scanning its ISBN barcode
     */
    public function showByIsbn($isbn)
    {
        // Query the database for the matching isbn_barcode
        $book = Book::where('isbn_barcode', $isbn)->first();

        // If no book matches the scanned barcode, return a 404 error
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found. Check the ISBN and try again.'
            ], 404);
        }

        // If found, return the book data to the frontend
        return response()->json([
            'success' => true,
            'data' => $book
        ], 200);
    }
}