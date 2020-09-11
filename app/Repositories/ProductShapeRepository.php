<?php

namespace App\Repositories;

use App\Models\ProductShape;
use App\Models\User;
use DB;

class ProductShapeRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return ProductShape::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = ProductShape::query();
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

    // create product shape entry
    public function create($input)
    {
        $model = new ProductShape();
        $model->fill($input);
        $model->save();
        return $model;
    }
}
