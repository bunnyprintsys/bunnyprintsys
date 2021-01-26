<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use App\Models\Multiplier;

trait HasMultiplierType
{
    // relationships
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

    // scopes
    public function scopeType($query, $value)
    {
        $value = $this->setTypeValue($value);
        // dd($value);
        $query = $query->whereHas('multipliers', function($query) use ($value) {
            $query->whereHas('multiplierType', function($query) use ($value) {
                $query->where('multiplier_types.id', $value);
            });
        });

        return $query;
    }

    public function scopeUnbindType($query, $value)
    {
        $value = $this->setTypeValue($value);

        $query = $query->whereDoesntHave('multipliers', function($query) use ($value) {
            $query->whereDoesntHave('multiplierType', function($query) use ($value) {
                $query->where('id', $value);
            });
        });

        // $query = $query->doesntHave('multipliers');
        // dd($query->toSql());

        return $query;
    }

    public function scopeIncludeMultiplier($query, $value)
    {

        if($value) {
            $query = $query->has('multipliers');
        }else {
            $query = $query->doesntHave('multipliers');
        }

        return $query;

    }

    // create multiplier when type available
    public function createMultiplierWithType($model, $input)
    {
        if (Arr::get($input, 'type', false)) {

            $input['multiplier_type_id'] = $this->setTypeValue($input['type']);

            $model->multipliers()->create($input);
        }
    }

    // update multiplier when type available
    public function updateMultiplierWithType($model, $input)
    {
        if(isset($input['type'])) {
            if($type = $input['type']) {
                switch($type) {
                    case 'customer':
                        $model->customerMultipliers->first()->update($input);
                        break;
                    case 'agent':
                        $model->agentMultipliers->first()->update($input);
                        break;
                }
            }
        }
    }

    // get multiplier with type
    public function getSingleMultiplierWithType($type = null)
    {
        $multiplier = '';
        if($type) {
            switch($type) {
                case 'customer':
                    $multiplier = $this->customerMultipliers->first() ? $this->customerMultipliers->first()->value : null;
                    break;
                case 'agent':
                    $multiplier = $this->agentMultipliers->first() ? $this->agentMultipliers->first()->value : null;
                    break;
            }
        }

        return $multiplier;
    }

    // overriding value of type into integer
    private function setTypeValue($value)
    {
        switch($value) {
            case 'customer':
                $value = 1;
                break;
            case 'agent':
                $value = 2;
                break;
        }

        return $value;
    }
}
