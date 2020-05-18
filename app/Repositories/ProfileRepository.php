<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\User;
use DB;

class ProfileRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Profile::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Profile::query();
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
     * @return Customer
     */
    public function create(User $user, $input)
    {
        $model = new Profile();
        $model->fill($input);
        // $model->profile_id = $user->profile_id;
        $model->save();
        return $model;
    }

    /**
     * @param User $user
     * @param Customer $model
     * @param $input
     * @return Customer
     */
    public function update(User $user, Profile $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
