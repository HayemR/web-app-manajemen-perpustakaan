<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasUuids, SoftDeletes;

    public function newUniqueId(): string
    {
        return (string) str()->uuid7();
    }

    protected $fillable = [
        'user_id',
        'book_id',
        'approved_by',
        'status',
        'request_date',
        'borrow_date',
        'due_date',
        'return_date',
        'fine_amount'
    ];

    // Automatically convert these database columns into Carbon Date objects in PHP
    protected $casts = [
        'request_date' => 'datetime',
        'borrow_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime',
        'fine_amount' => 'decimal:2',
    ];

    // Relationship: The transaction belongs to the Student (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: The transaction belongs to a specific Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relationship: The transaction was approved by a Librarian (User)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}