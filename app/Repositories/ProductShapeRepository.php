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
        $query = ProductShape::with(['product', 'shape']);

        $query->select('product_shapes.*');

        $sortBy = array_unique($sortBy);
        foreach ($sortBy as $key => $direction) {
            switch ($key) {
                case 'shape_name':
                    $query->leftJoin('shapes', 'shapes.id', '=', 'product_shapes.shape_id');
                    $sortBy['shapes.name'] = $direction;
                    unset($sortBy[$key]);
                    break;
            }
        }

        if (!empty($filter)) {
            $query->filter($filter, 'product_shapes');
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy, 'product_shapes');
        }

        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create model entry
    public function create($input)
    {
        $model = new ProductShape();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // udpate model entry
    public function update(ProductShape $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
