<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderQuantity extends Model
{
    protected $fillable = [
        'name', 'qty'
    ];
}
