<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $fillable = [
        'car_id',
        'buyer_id',
        'dealer_id',
        'subject',
        'status',
        'last_message_at',
    ];
protected $casts = [
    'last_message_at' => 'datetime',
];
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }

    public function messages()
    {
        return $this->hasMany(InquiryMessage::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(InquiryMessage::class)->latestOfMany();
    }
}
