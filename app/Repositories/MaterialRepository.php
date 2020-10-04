<?php

namespace App\Repositories;

use App\Models\Material;
use App\Models\User;
use DB;

class MaterialRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Material::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
/*
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = Material::with(['productMaterials', 'productShapes.product']);
        $query->select('shapes.*');

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
    } */

    // create model entry
    public function create($input)
    {
        $model = new Material();
        $model->fill($input);
        $model->save();
        return $model;
    }
}
