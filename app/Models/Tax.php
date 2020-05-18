<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name', 'desc', 'rate'
    ];

    // relationships
    public function profiles()
    {
        return $this->belongsToMany('App\Profile');
    }
}
