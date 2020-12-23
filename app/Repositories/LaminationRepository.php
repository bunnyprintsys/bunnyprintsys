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

    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = Lamination::query();

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
        $model = new Lamination();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // create model entry
    public function update(Lamination $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
