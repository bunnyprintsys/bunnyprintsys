<?php

namespace App\Repositories;

use App\Models\Deal;
use App\Models\User;
use DB;

class DealRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Deal::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Deal::query();
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

    /**
     * @param User $user
     * @param $input
     * @return Deal
     */
    public function create(User $user, $input)
    {
        $model = new Deal();
        $model->fill($input);
        // $model->profile_id = $user->profile_id;
        $model->save();
        return $model;
    }

    /**
     * @param User $user
     * @param Deal $model
     * @param $input
     * @return Deal
     */
    public function update(User $user, Deal $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
