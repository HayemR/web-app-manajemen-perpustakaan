<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasUuids, SoftDeletes; 

    public function newUniqueId(): string
    {
        return (string) str()->uuid7();
    }

    // Columns that can be inserted/updated via your API
    protected $fillable = [
        'category_id',
        'isbn_barcode',
        'title',
        'author',
        'publisher',
        'published_year',
        'stock',
        'cover_image_url',
        'description',
        'is_available'
    ];

    // Optional: Ensure stock is always treated as an integer
    protected $casts = [
        'stock' => 'integer',
        'is_available' => 'boolean',
    ];

    // Relationship: A book belongs to one Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: A book can be borrowed many times (Many Transactions)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}