<?php

namespace App\Repositories;

use App\Models\ProductFinishing;
use App\Models\User;
use DB;

class ProductFinishingRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return ProductFinishing::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = ProductFinishing::with(['product', 'finishing']);
        $query->select('product_finishings.*');

        $sortBy = array_unique($sortBy);
        foreach ($sortBy as $key => $direction) {
            switch ($key) {
                case 'finishing_name':
                    $query->leftJoin('finishings', 'finishings.id', '=', 'product_finishings.finishing_id');
                    $sortBy['finishings.name'] = $direction;
                    unset($sortBy[$key]);
                    break;
            }
        }

        if (!empty($filter)) {
            $query->filter($filter, 'product_finishings');
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy, 'product_finishings');
        }

        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create model entry
    public function create($input)
    {
        $model = new ProductFinishing();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // udpate model entry
    public function update(ProductFinishing $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
