<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductShape extends Model
{
    protected $fillable = [
        'product_id', 'shape_id', 'multiplier'
    ];
}
