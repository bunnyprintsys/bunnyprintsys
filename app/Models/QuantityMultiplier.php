<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuantityMultiplier extends Model
{
    protected $fillable = [
        'multiplier', 'min', 'max', 'product_id'
    ];
}
