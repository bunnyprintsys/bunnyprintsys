<?php

namespace App\Repositories;

use App\Models\OrderQuantity;
use App\Models\User;
use DB;

class OrderQuantityRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return OrderQuantity::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = OrderQuantity::query();

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
        $model = new OrderQuantity();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // udpate model entry
    public function update(OrderQuantity $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
