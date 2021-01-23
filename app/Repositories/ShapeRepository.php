<?php

namespace App\Repositories;

use App\Models\Shape;
use App\Models\User;
use DB;

class ShapeRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Shape::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = Shape::query();

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
        $model = new Shape();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // create model entry
    public function update(Shape $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
