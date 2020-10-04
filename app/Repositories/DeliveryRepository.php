<?php

namespace App\Repositories;

use App\Models\Delivery;
use App\Models\User;
use DB;

class DeliveryRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Delivery::filter($input)->first();
    }

    // create model entry
    public function create($input)
    {
        $model = new Delivery();
        $model->fill($input);
        $model->save();
        return $model;
    }
}
