<?php

namespace App\Repositories;

use App\Models\Bank;

class BankRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Bank::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */

    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = Bank::query();

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
        $model = new Bank();
        $model->fill($input);
        $model->save();
        return $model;
    }
}
