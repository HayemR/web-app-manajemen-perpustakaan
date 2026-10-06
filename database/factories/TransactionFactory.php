<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'approved_by' => null,
            'status' => Transaction::STATUS_PENDING,
            'request_date' => now(),
            'borrow_date' => null,
            'due_date' => null,
            'return_date' => null,
            'fine_amount' => 0,
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => [
            'status' => Transaction::STATUS_ACTIVE,
            'approved_by' => User::factory()->state(['role' => 'librarian']),
            'borrow_date' => now()->subDays(2),
            'due_date' => now()->addDays(5),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn () => [
            'status' => Transaction::STATUS_OVERDUE,
            'approved_by' => User::factory()->state(['role' => 'librarian']),
            'borrow_date' => now()->subDays(10),
            'due_date' => now()->subDays(3),
            'fine_amount' => 3000,
        ]);
    }

    public function returned(): static
    {
        return $this->state(fn () => [
            'status' => Transaction::STATUS_RETURNED,
            'approved_by' => User::factory()->state(['role' => 'librarian']),
            'borrow_date' => now()->subDays(8),
            'due_date' => now()->subDays(1),
            'return_date' => now(),
            'fine_amount' => 1000,
        ]);
    }
}
