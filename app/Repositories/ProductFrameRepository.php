<?php

namespace App\Repositories;

use App\Models\ProductFrame;
use App\Models\User;
use DB;

class ProductFrameRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return ProductFrame::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = ProductFrame::with(['product', 'material']);
        $query->select('product_materials.*');

        $sortBy = array_unique($sortBy);
        foreach ($sortBy as $key => $direction) {
            switch ($key) {
                case 'frame_name':
                    $query->leftJoin('frames', 'frames.id', '=', 'product_frames.frame_id');
                    $sortBy['frames.name'] = $direction;
                    unset($sortBy[$key]);
                    break;
            }
        }

        if (!empty($filter)) {
            $query->filter($filter, 'product_frames');
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy, 'product_frames');
        }

        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create model entry
    public function create($input)
    {
        $model = new ProductFrame();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // udpate model entry
    public function update(ProductFrame $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
