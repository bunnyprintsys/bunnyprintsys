<?php

namespace App\Repositories;

use App\Models\Finishing;
use App\Models\User;
use DB;

class FinishingRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Finishing::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */

    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = Finishing::query();

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
        $model = new Finishing();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // create model entry
    public function update(Finishing $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
