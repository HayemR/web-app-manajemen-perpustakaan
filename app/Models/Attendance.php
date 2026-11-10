<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasUuids, SoftDeletes;

    public function newUniqueId(): string
    {
        return (string) str()->uuid7();
    }

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_institution',
        'purpose',
        'visitor_type',
        'type', // 'masuk' or 'keluar'
        'scan_time'
    ];

    protected $casts = [
        'scan_time' => 'datetime',
    ];

    // Relationship: The attendance record belongs to a User (if not a guest)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}