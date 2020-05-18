<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLamination extends Model
{
    protected $fillable = [
        'product_id', 'lamination_id', 'multiplier'
    ];
}
