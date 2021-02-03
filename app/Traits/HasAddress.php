<?php

namespace App\Traits;

use App\Models\Address;

trait HasAddress
{
    // relationships
    public function addresses()
    {
        return $this->morphMany(Address::class, 'typeable');
    }
}
