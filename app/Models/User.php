<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'country_code',
        'role',
        'timezone',
        'status',
    ];
    public function showrooms(): HasMany
    {
        return $this->hasMany(Showroom::class, 'user_id', 'id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'user_id', 'id');
    }
    public function sentMessages()
    {
        return $this->hasMany(InquiryMessage::class, 'sender_id');
    }
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function testDrives(): HasMany
    {
        return $this->hasMany(TestDrive::class);
    }

    public function favoriteCars()
    {
        return $this->belongsToMany(Car::class, 'favorites', 'user_id', 'car_id');
    }
}
