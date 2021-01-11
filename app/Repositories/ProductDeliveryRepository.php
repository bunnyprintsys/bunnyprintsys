<?php

namespace App\Repositories;

use App\Models\ProductDelivery;
use App\Models\User;
use DB;

class ProductDeliveryRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return ProductDelivery::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = ProductDelivery::with(['product', 'delivery']);
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
        $query->select('product_deliveries.*');

        $sortBy = array_unique($sortBy);
        foreach ($sortBy as $key => $direction) {
            switch ($key) {
                case 'delivery_name':
                    $query->leftJoin('deliveries', 'deliveries.id', '=', 'product_deliveries.delivery_id');
                    $sortBy['deliveries.name'] = $direction;
                    unset($sortBy[$key]);
                    break;
            }
        }

        if (!empty($filter)) {
            $query->filter($filter, 'product_deliveries');
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy, 'product_deliveries');
        }

        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create model entry
    public function create($input)
    {
        $model = new ProductDelivery();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // udpate model entry
    public function update(ProductDelivery $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
