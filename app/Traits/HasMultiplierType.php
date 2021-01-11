<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use App\Models\Multiplier;

trait HasMultiplierType
{
    public function multipliers()
    {
        return $this->morphMany(Multiplier::class, 'categoryable');
    }

    public function customerMultipliers()
    {
        return $this->multipliers()->whereHas('multiplierType', function($query) {
            $query->where('id', 1);
        });
    }

    public function agentMultipliers()
    {
        return $this->multipliers()->whereHas('multiplierType', function($query) {
            $query->where('id', 2);
        });
    }
}
