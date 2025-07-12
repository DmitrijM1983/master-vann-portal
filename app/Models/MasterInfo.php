<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MasterInfo extends Model
{
    use HasFactory;

    protected $table = 'masters_info';

    protected $fillable = [
        'user_id',
        'master_photo',
        'job_photos',
        'experience',
        'guarantee',
        'rating',
        'description'
    ];

    protected $casts = [
        'job_photos' => 'array'
    ];

    public function getPhotoUrl(): string
    {
        return Storage::disk('public')->url($this->master_photo);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
