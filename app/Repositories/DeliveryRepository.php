<?php

namespace App\Repositories;

use App\Models\Delivery;
use App\Models\User;
use DB;

class DeliveryRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Delivery::filter($input)->first();
    }

    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = Delivery::query();

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
        $model = new Delivery();
        $model->fill($input);
        $model->save();
        return $model;
    }
}
