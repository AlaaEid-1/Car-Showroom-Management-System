<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
        use SoftDeletes;

    protected $fillable = [
        'showroom_id',
        'user_id',
        'title',
        'brand',
        'model',
        'year',
        'price',
        'description',
        'status',
    ];

    public const STATUSES = [
        'draft' => 'Draft',
        'published' => 'Published',
        'sold' => 'Sold',
    ];
    public function showroom(): BelongsTo
    {
        return $this->belongsTo(Showroom::class, 'showroom_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderByDesc('is_main');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'car_id', 'id');
    }

    public function testDrives(): HasMany
    {
        return $this->hasMany(TestDrive::class, 'car_id', 'id');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'car_id', 'user_id');
    }

    public function getMainImageAttribute()
    {
        return $this->images()
            ->where('is_main', true)
            ->first();
    }
    public function mainImage()
    {
        return $this->hasOne(CarImage::class)
            ->where('is_main', true);
    }
}
