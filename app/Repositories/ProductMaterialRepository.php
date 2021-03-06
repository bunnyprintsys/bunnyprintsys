<?php

namespace App\Repositories;

use App\Models\ProductMaterial;
use App\Models\User;
use DB;

class ProductMaterialRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        $model = ProductMaterial::filter($input)->first();
        return $model;
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = ProductMaterial::with(['product', 'material']);

        $query->select('product_materials.*');

        $sortBy = array_unique($sortBy);
        foreach ($sortBy as $key => $direction) {
            switch ($key) {
                case 'material_name':
                    $query->leftJoin('materials', 'materials.id', '=', 'product_materials.material_id');
                    $sortBy['materials.name'] = $direction;
                    unset($sortBy[$key]);
                    break;
            }
        }

        if (!empty($filter)) {
            $query->filter($filter, 'product_materials');
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy, 'product_materials');
        }

        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create model entry
    public function create($input)
    {
        $model = new ProductMaterial();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // udpate model entry
    public function update(ProductMaterial $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
