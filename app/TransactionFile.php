<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionFile extends Model
{
    protected $fillable = [
        'url'
    ];

    // relationships
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
