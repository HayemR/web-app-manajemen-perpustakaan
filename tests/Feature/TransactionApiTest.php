<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_request_book_loan_when_stock_is_available(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id, 'stock' => 3]);
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->postJson('/api/transactions', [
            'user_id' => $student->id,
            'book_id' => $book->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $student->id,
            'book_id' => $book->id,
            'status' => 'pending',
        ]);
    }

    public function test_student_cannot_request_loan_when_stock_is_zero(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id, 'stock' => 0]);
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->postJson('/api/transactions', [
            'user_id' => $student->id,
            'book_id' => $book->id,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Buku ini tidak dapat dipinjam karena stok saat ini habis.',
            ]);
    }

    public function test_student_cannot_make_duplicate_active_or_pending_request_for_same_book(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id, 'stock' => 5]);
        $student = User::factory()->create();

        Transaction::factory()->create([
            'user_id' => $student->id,
            'book_id' => $book->id,
            'status' => 'pending',
        ]);

        $response = $this->postJson('/api/transactions', [
            'user_id' => $student->id,
            'book_id' => $book->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error');
    }

    public function test_librarian_can_approve_pending_loan_and_decrement_stock(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id, 'stock' => 4]);
        $student = User::factory()->create();
        $librarian = User::factory()->create(['role' => 'librarian']);

        $transaction = Transaction::factory()->create([
            'user_id' => $student->id,
            'book_id' => $book->id,
            'status' => 'pending',
        ]);

        $response = $this->postJson("/api/transactions/{$transaction->id}/approve", [
            'approved_by' => $librarian->id,
            'loan_days' => 7,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.approved_by', $librarian->id);

        $this->assertEquals(3, $book->fresh()->stock);
        $this->assertNotNull($transaction->fresh()->borrow_date);
        $this->assertNotNull($transaction->fresh()->due_date);
    }

    public function test_librarian_cannot_approve_non_pending_transaction(): void
    {
        $transaction = Transaction::factory()->active()->create();
        $librarian = User::factory()->create();

        $response = $this->postJson("/api/transactions/{$transaction->id}/approve", [
            'approved_by' => $librarian->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error');
    }

    public function test_librarian_can_reject_pending_transaction(): void
    {
        $transaction = Transaction::factory()->create(['status' => 'pending']);
        $librarian = User::factory()->create();

        $response = $this->postJson("/api/transactions/{$transaction->id}/reject", [
            'approved_by' => $librarian->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'rejected');
    }

    public function test_librarian_can_return_book_on_time_without_fine_and_increment_stock(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id, 'stock' => 2]);
        $student = User::factory()->create();
        $librarian = User::factory()->create();

        $transaction = Transaction::factory()->create([
            'user_id' => $student->id,
            'book_id' => $book->id,
            'approved_by' => $librarian->id,
            'status' => 'active',
            'borrow_date' => Carbon::now()->subDays(3),
            'due_date' => Carbon::now()->addDays(4),
        ]);

        $response = $this->postJson("/api/transactions/{$transaction->id}/return");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'returned')
            ->assertJsonPath('data.fine_amount', '0.00');

        $this->assertEquals(3, $book->fresh()->stock);
        $this->assertNotNull($transaction->fresh()->return_date);
    }

    public function test_librarian_can_return_book_overdue_with_calculated_fine(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id, 'stock' => 1]);
        $student = User::factory()->create();
        $librarian = User::factory()->create();

        // 3 days late
        $transaction = Transaction::factory()->create([
            'user_id' => $student->id,
            'book_id' => $book->id,
            'approved_by' => $librarian->id,
            'status' => 'active',
            'borrow_date' => Carbon::now()->subDays(10),
            'due_date' => Carbon::now()->subDays(3),
        ]);

        $response = $this->postJson("/api/transactions/{$transaction->id}/return");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'returned')
            ->assertJsonPath('data.fine_amount', '3000.00');

        $this->assertEquals(2, $book->fresh()->stock);
        $this->assertEquals(3000.00, (float) $transaction->fresh()->fine_amount);
    }

    public function test_check_overdue_command_marks_active_loans_overdue(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);
        $student = User::factory()->create();

        $overdueTx = Transaction::factory()->create([
            'user_id' => $student->id,
            'book_id' => $book->id,
            'status' => 'active',
            'due_date' => Carbon::now()->subDays(2),
        ]);

        $this->artisan('library:check-overdue')
            ->assertSuccessful();

        $this->assertEquals('overdue', $overdueTx->fresh()->status);
        $this->assertGreaterThan(0, (float) $overdueTx->fresh()->fine_amount);
    }
}
