<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CityUser extends Model
{
    protected $table = 'city_user';

    protected $fillable = [
        'city_id',
        'user_id'
    ];

    public function city(): hasOne
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
}
