<?php

namespace App\Repositories;

use App\Models\ProductLamination;
use App\Models\User;
use DB;

class ProductLaminationRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return ProductLamination::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = ProductLamination::with(['product', 'lamination']);
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
        $query->select('product_laminations.*');

        $sortBy = array_unique($sortBy);
        foreach ($sortBy as $key => $direction) {
            switch ($key) {
                case 'lamination_name':
                    $query->leftJoin('laminations', 'laminations.id', '=', 'product_laminations.lamination_id');
                    $sortBy['laminations.name'] = $direction;
                    unset($sortBy[$key]);
                    break;
            }
        }

        if (!empty($filter)) {
            $query->filter($filter, 'product_laminations');
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy, 'product_laminations');
        }

        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create model entry
    public function create($input)
    {
        $model = new ProductLamination();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // udpate model entry
    public function update(ProductLamination $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
