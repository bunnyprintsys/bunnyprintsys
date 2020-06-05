<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\User;
use DB;

class CountryRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Country::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Country::query();
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

    /**
     * @param User $user
     * @param $input
     * @return Country
     */
    public function create(User $user, $input)
    {
        $model = new Country();
        $model->fill($input);
        // $model->profile_id = $user->profile_id;
        $model->save();
        return $model;
    }

    /**
     * @param User $user
     * @param Country $model
     * @param $input
     * @return Country
     */
    public function update(User $user, Country $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
