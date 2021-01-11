<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultiplierType extends Model
{
    protected $fillable = [
        'name'
    ];

    // relationships
    public function multipliers()
    {
        return $this->hasMany(Multiplier::class);
    }
}
