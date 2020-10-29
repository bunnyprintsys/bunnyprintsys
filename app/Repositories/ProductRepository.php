<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use DB;

class ProductRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Product::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Product::query();
        if (!empty($filter)) {
            $query->filter($filter);
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy);
        }
        // dd($query->get());
        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create product entry
    public function create($input)
    {
        $model = new Product();
        $model->fill($input);
        $model->save();
        return $model;
    }
}
