<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDelivery extends Model
{
    protected $fillable = [
        'product_id', 'delivery_id', 'multiplier'
    ];
}
