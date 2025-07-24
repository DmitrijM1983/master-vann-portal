<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'master_id',
        'user_id',
        'service_id',
        'grade',
        'content',
        'service_provided',
        'images',
        'answer'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function service(): HasOne
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }
}
