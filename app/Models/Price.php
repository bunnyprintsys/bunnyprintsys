<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'min', 'max', 'name', 'amount'
    ];

    // getter
    public function getAmountAttribute($value)
    {
        return number_format($value/ 100, 2);
    }
}
