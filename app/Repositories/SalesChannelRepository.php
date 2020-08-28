<?php

namespace App\Repositories;

use App\Models\SalesChannel;
use App\Models\User;
use DB;

class SalesChannelRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return SalesChannel::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = SalesChannel::query();
        if (!empty($filter)) {
            $query->filter($filter);
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy);
        }
        // dd($query->get());
        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }
}
