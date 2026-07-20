<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
    ];

    protected $casts = [
        'status' => \App\Enums\DealerRequestStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
