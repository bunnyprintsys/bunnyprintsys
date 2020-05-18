<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function getOneById($id)
    {
        return User::find($id);
    }

    /**
     * @param $input
     * @return User
     */
    public function create($input)
    {
        $model = new User();
        $model->fill($input);
        $model->save();
        return $model;
    }

    /**
     * @param User $model
     * @param $input
     * @return User
     */
    public function update(User $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }

    /**
     * @param array $filter
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getOne($filter = [])
    {
        $query = User::query();

        if (!empty($filter)) {
            $query->filter($filter);
        }
        return $query->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return User[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = User::query();

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
     * @param $key
     * @param $value
     * @return bool
     */
    public function isExisted($key, $value)
    {
        return User::query()->where($key, $value)->exists();
    }
}
