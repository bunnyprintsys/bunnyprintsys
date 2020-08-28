<?php

namespace App\Repositories;

use App\Models\Status;
use App\Models\User;
use DB;

class StatusRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Status::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Status::query();
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
