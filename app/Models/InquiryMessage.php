<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryMessage extends Model
{
    protected $fillable = [
        'inquiry_id',
        'sender_id',
        'message',
        'read_at'
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }
public function messages()
{
    return $this->hasMany(InquiryMessage::class);
}
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
