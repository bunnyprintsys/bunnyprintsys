<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankBinding extends Model
{
    protected $fillable = [
        'bankable_type', 'bankable_id', 'bank_id'
    ];

    public function bankable()
    {
        return $this->morphTo();
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    // scopes
    public function scopeFilter($query, $input)
    {
        return $query;
    }

    public function scopeSortBy($query, $input)
    {
        return $query;
    }
}
