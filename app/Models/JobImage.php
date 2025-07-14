<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobImage extends Model
{
    protected $table = 'job_images';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image'
    ];

}
