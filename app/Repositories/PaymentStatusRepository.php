<?php

namespace App\Repositories;

use App\Models\PaymentStatus;

class PaymentStatusRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return PaymentStatus::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */

    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = PaymentStatus::query();

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
        $model = new PaymentStatus();
        $model->fill($input);
        $model->save();
        return $model;
    }
}
