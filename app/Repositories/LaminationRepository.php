<?php

namespace App\Repositories;

use App\Models\Lamination;
use App\Models\User;
use DB;

class LaminationRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Lamination::filter($input)->first();
    }

    // create model entry
    public function create($input)
    {
        $model = new Lamination();
        $model->fill($input);
        $model->save();
        return $model;
    }
}
