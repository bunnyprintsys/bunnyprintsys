<?php

namespace App\Repositories;

use App\Models\Tax;
use App\Models\User;
use DB;

class TaxRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Tax::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Tax::query();
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
}
