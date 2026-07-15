<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Showroom extends Model
{
       protected $fillable = [
        'user_id',
        'name',
        'description',
        'location',
        'phone',
        'logo',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'showroom_id', 'id');
    }
}
