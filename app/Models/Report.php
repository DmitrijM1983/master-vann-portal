<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'service',
        'price',
        'date',
        'materials_price',
        'transports_price',
        'other_price'
    ];
}
