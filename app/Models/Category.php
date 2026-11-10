<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasUuids, SoftDeletes;

    public function newUniqueId(): string
    {
        return (string) str()->uuid7();
    }

    protected $fillable = [
        'name'
    ];

    // Relationship: A category can have many books
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}