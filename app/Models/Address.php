<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'block', 'unit', 'building_name', 'road_name', 'postcode', 'state', 'full_address', 'is_primary'
    ];
}
