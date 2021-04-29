<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\User;
use DB;

class AddressRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Address::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Address::query();
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
     * @param $input
     * @return Address
     */
    public function create(User $user, $input)
    {
        $model = new Address();
        $model->fill($input);
        // $model->profile_id = $user->profile_id;
        $model->save();
        return $model;
    }

    /**
     * @param Address $model
     * @param $input
     * @return Address
     */
    public function update(Address $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
