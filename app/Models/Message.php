<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends Model
{
    protected $fillable = [
        'user_from',
        'user_to',
        'content',
        'images'
    ];

    public function userFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_from', 'id');
    }

    public function userTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_to', 'id');
    }

    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class, 'message_id', 'id');
    }
}
