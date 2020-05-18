<?php

namespace App\Repositories;

use App\Models\Multiplier;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class MultiplierRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function getOneById($id)
    {
        return Multiplier::find($id);
    }

    /**
     * @param $input
     * @return Multiplier
     */
    public function create($input)
    {
        $model = new Multiplier();
        $model->fill($input);
        $model->save();
        return $model;
    }

    /**
     * @param Multiplier $model
     * @param $input
     * @return Multiplier
     */
    public function update(Multiplier $model, $input)
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
        $query = Multiplier::query();

        if (!empty($filter)) {
            $query->filter($filter);
        }
        return $query->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return Multiplier[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = Multiplier::query();

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
        return Multiplier::query()->where($key, $value)->exists();
    }
}
