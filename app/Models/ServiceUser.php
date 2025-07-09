<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceUser extends Model
{
    protected $table = 'service_user';

    protected $fillable = [
        'service_id',
        'user_id',
        'price'
    ];

    public function service(): hasOne
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }
}
