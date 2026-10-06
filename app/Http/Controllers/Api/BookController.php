<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookMetadataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function __construct(
        protected BookMetadataService $metadataService
    ) {}

    /**
     * Display a listing of books.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Book::with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        // Search by title, author, or ISBN
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn_barcode', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        // Filter available stock only
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        $books = $request->boolean('all')
            ? $query->latest()->get()
            : $query->latest()->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $books,
        ]);
    }

    /**
     * Lookup metadata by ISBN from external APIs (Google Books / Open Library).
     */
    public function lookupIsbn(Request $request): JsonResponse
    {
        $request->validate([
            'isbn' => ['required', 'string'],
        ]);

        $isbn = $request->query('isbn');
        $metadata = $this->metadataService->lookupByIsbn($isbn);

        if (! $metadata) {
            return response()->json([
                'status' => 'error',
                'message' => 'Metadata buku untuk ISBN tersebut tidak ditemukan pada Google Books maupun Open Library.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $metadata,
        ]);
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'isbn_barcode' => ['nullable', 'string', 'max:50', 'unique:books,isbn_barcode'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'published_year' => ['required', 'string', 'max:10'],
            'stock' => ['required', 'integer', 'min:0'],
            'cover_image' => ['nullable', 'image', 'max:5120'], // file upload
            'cover_image_url' => ['nullable', 'string', 'max:2048'], // or direct url
            'description' => ['nullable', 'string'],
        ]);

        // Handle cover image upload if file is provided
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('books', 'public');
            $validated['cover_image_url'] = Storage::url($path);
        }
        unset($validated['cover_image']);

        $book = Book::create($validated);
        $book->load('category');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil ditambahkan.',
            'data' => $book,
        ], 201);
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book): JsonResponse
    {
        $book->load('category');
        $book->loadCount('transactions');

        return response()->json([
            'status' => 'success',
            'data' => $book,
        ]);
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['sometimes', 'required', 'uuid', 'exists:categories,id'],
            'isbn_barcode' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('books', 'isbn_barcode')->ignore($book->id),
            ],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'author' => ['sometimes', 'required', 'string', 'max:255'],
            'publisher' => ['sometimes', 'required', 'string', 'max:255'],
            'published_year' => ['sometimes', 'required', 'string', 'max:10'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'cover_image_url' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('books', 'public');
            $validated['cover_image_url'] = Storage::url($path);
        }
        unset($validated['cover_image']);

        $book->update($validated);
        $book->load('category');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil diperbarui.',
            'data' => $book,
        ]);
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book): JsonResponse
    {
        // Don't delete if there are ongoing active or pending loans
        $activeLoansCount = $book->transactions()
            ->whereIn('status', ['pending', 'active', 'overdue'])
            ->count();

        if ($activeLoansCount > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku tidak dapat dihapus karena masih sedang dipinjam atau menunggu persetujuan.',
                'active_loans_count' => $activeLoansCount,
            ], 422);
        }

        $book->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil dihapus.',
        ]);
    }
}
