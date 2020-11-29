<?php

namespace App\Repositories;

use App\Models\PaymentTerm;

class PaymentTermRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return PaymentTerm::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */

    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = PaymentTerm::query();

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
        $model = new PaymentTerm();
        $model->fill($input);
        $model->save();
        return $model;
    }
}
