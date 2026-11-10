<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'nis', 'rfid_uid', 'role', 'class_name'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    // Add HasApiTokens here:
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    // Enforce UUID v7 for the Primary Key
    public function newUniqueId(): string
    {
        return (string) str()->uuid7();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship: A user can have many borrowing transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // Relationship: A user can have many attendance records
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }
}