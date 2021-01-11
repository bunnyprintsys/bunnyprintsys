<?php

namespace App\Repositories;

use App\Models\QuantityMultiplier;
use App\Models\User;
use DB;

class QuantityMultiplierRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return QuantityMultiplier::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = QuantityMultiplier::query();
        if($type = $filter['type']) {
            switch($type) {
                case 'customer':
                    $type = 1;
                    break;
                case 'agent':
                    $type = 2;
                    break;
            }
            $query->whereHas('multipliers.multiplierType', function($query) use($type){
                $query->where('id', $type);
            });
        }

        if (!empty($filter)) {
            $query->filter($filter);
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy);
        }

        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create model entry
    public function create($input)
    {
        $model = new QuantityMultiplier();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // udpate model entry
    public function update(QuantityMultiplier $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
