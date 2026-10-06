<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::with(['user', 'book.category', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->query('book_id'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('nis', 'like', "%{$search}%");
                })->orWhereHas('book', function ($bq) use ($search) {
                    $bq->where('title', 'like', "%{$search}%")
                       ->orWhere('isbn_barcode', 'like', "%{$search}%");
                });
            });
        }

        $transactions = $query->latest('request_date')->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $transactions,
        ]);
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load(['user', 'book.category', 'approver']);

        // Check if currently overdue
        $currentFineEstimate = $this->calculateAccruedFine($transaction);

        $responseData = $transaction->toArray();
        $responseData['estimated_fine_amount'] = $currentFineEstimate;

        return response()->json([
            'status' => 'success',
            'data' => $responseData,
        ]);
    }

    /**
     * Student requests a book loan (status = pending).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'book_id' => ['required', 'uuid', 'exists:books,id'],
        ]);

        $book = Book::findOrFail($validated['book_id']);

        // Validasi: Buku hanya bisa diajukan peminjamannya jika stock > 0
        if ($book->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku ini tidak dapat dipinjam karena stok saat ini habis.',
            ], 422);
        }

        // Cek apakah user sedang meminjam atau sudah mengajukan buku yang sama dan belum dikembalikan/selesai
        $existingActive = Transaction::where('user_id', $validated['user_id'])
            ->where('book_id', $validated['book_id'])
            ->whereIn('status', [Transaction::STATUS_PENDING, Transaction::STATUS_ACTIVE, Transaction::STATUS_OVERDUE])
            ->first();

        if ($existingActive) {
            return response()->json([
                'status' => 'error',
                'message' => "Pengguna masih memiliki transaksi '{$existingActive->status}' untuk buku ini.",
            ], 422);
        }

        $transaction = Transaction::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'status' => Transaction::STATUS_PENDING,
            'request_date' => Carbon::now(),
            'fine_amount' => 0,
        ]);

        $transaction->load(['user', 'book.category']);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan peminjaman buku berhasil dibuat. Menunggu persetujuan petugas.',
            'data' => $transaction,
        ], 201);
    }

    /**
     * Librarian approves the loan request (pending -> active).
     */
    public function approve(Request $request, Transaction $transaction): JsonResponse
    {
        $validated = $request->validate([
            'approved_by' => ['required', 'uuid', 'exists:users,id'],
            'loan_days' => ['nullable', 'integer', 'min:1', 'max:90'],
        ]);

        // State check: Only 'pending' can be approved
        if ($transaction->status !== Transaction::STATUS_PENDING) {
            return response()->json([
                'status' => 'error',
                'message' => "Transaksi tidak dapat disetujui karena status saat ini adalah '{$transaction->status}'.",
            ], 422);
        }

        $loanDays = $validated['loan_days'] ?? config('library.loan_duration_days', 7);

        return DB::transaction(function () use ($transaction, $validated, $loanDays) {
            // Lock book row to prevent race conditions on stock decrement
            $book = Book::where('id', $transaction->book_id)->lockForUpdate()->firstOrFail();

            if ($book->stock <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Persetujuan gagal karena stok buku saat ini sudah habis.',
                ], 422);
            }

            // Decrement book stock by 1
            $book->decrement('stock');

            $borrowDate = Carbon::now();
            $dueDate = (clone $borrowDate)->addDays($loanDays)->endOfDay();

            $transaction->update([
                'status' => Transaction::STATUS_ACTIVE,
                'approved_by' => $validated['approved_by'],
                'borrow_date' => $borrowDate,
                'due_date' => $dueDate,
            ]);

            $transaction->load(['user', 'book.category', 'approver']);

            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman buku berhasil disetujui.',
                'data' => $transaction,
            ]);
        });
    }

    /**
     * Librarian rejects the loan request (pending -> rejected).
     */
    public function reject(Request $request, Transaction $transaction): JsonResponse
    {
        $validated = $request->validate([
            'approved_by' => ['required', 'uuid', 'exists:users,id'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        if ($transaction->status !== Transaction::STATUS_PENDING) {
            return response()->json([
                'status' => 'error',
                'message' => "Hanya pengajuan dengan status 'pending' yang dapat ditolak. Status saat ini: '{$transaction->status}'.",
            ], 422);
        }

        $transaction->update([
            'status' => Transaction::STATUS_REJECTED,
            'approved_by' => $validated['approved_by'],
        ]);

        $transaction->load(['user', 'book.category', 'approver']);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan peminjaman buku ditolak.',
            'data' => $transaction,
        ]);
    }

    /**
     * Librarian confirms return of the physical book (active/overdue -> returned).
     */
    public function returnBook(Request $request, Transaction $transaction): JsonResponse
    {
        // Valid status: active or overdue
        if (! in_array($transaction->status, [Transaction::STATUS_ACTIVE, Transaction::STATUS_OVERDUE])) {
            return response()->json([
                'status' => 'error',
                'message' => "Hanya peminjaman dengan status 'active' atau 'overdue' yang dapat dikembalikan. Status saat ini: '{$transaction->status}'.",
            ], 422);
        }

        return DB::transaction(function () use ($transaction) {
            $returnDate = Carbon::now();
            $fineRate = (float) config('library.fine_per_day', 1000);
            $fineAmount = 0.00;
            $daysLate = 0;

            if ($transaction->due_date && $returnDate->greaterThan($transaction->due_date)) {
                // Calculate calendar days difference
                $daysLate = (int) $transaction->due_date->copy()->startOfDay()->diffInDays($returnDate->copy()->startOfDay());
                if ($daysLate < 1) {
                    $daysLate = 1;
                }
                $fineAmount = $daysLate * $fineRate;
            }

            // Restore book stock (+1)
            $book = Book::where('id', $transaction->book_id)->lockForUpdate()->firstOrFail();
            $book->increment('stock');

            $transaction->update([
                'status' => Transaction::STATUS_RETURNED,
                'return_date' => $returnDate,
                'fine_amount' => $fineAmount,
            ]);

            $transaction->load(['user', 'book.category', 'approver']);

            return response()->json([
                'status' => 'success',
                'message' => $daysLate > 0
                    ? "Buku berhasil dikembalikan dengan keterlambatan {$daysLate} hari. Denda yang dikenakan: Rp " . number_format($fineAmount, 0, ',', '.') . "."
                    : "Buku berhasil dikembalikan tepat waktu tanpa denda.",
                'data' => $transaction,
                'meta' => [
                    'days_late' => $daysLate,
                    'fine_per_day' => $fineRate,
                    'fine_amount' => $fineAmount,
                ],
            ]);
        });
    }

    /**
     * Calculate accrued fine if current date is past due date.
     */
    protected function calculateAccruedFine(Transaction $transaction): float
    {
        if (in_array($transaction->status, [Transaction::STATUS_ACTIVE, Transaction::STATUS_OVERDUE]) && $transaction->due_date) {
            $now = Carbon::now();
            if ($now->greaterThan($transaction->due_date)) {
                $daysLate = (int) $transaction->due_date->copy()->startOfDay()->diffInDays($now->copy()->startOfDay());
                if ($daysLate < 1) {
                    $daysLate = 1;
                }
                $fineRate = (float) config('library.fine_per_day', 1000);
                return (float) ($daysLate * $fineRate);
            }
        }

        return (float) $transaction->fine_amount;
    }
}
