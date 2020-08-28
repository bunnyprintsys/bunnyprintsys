<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;
use DB;

class TransactionRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Transaction::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Transaction::query();
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
     * @return Transaction
     */
    public function create(User $user, $input)
    {
        $model = new Transaction();
        $model->fill($input);
        $model->profile_id = $user->profile_id;
        $model->created_by = $user->id;
        $model->save();
        return $model;
    }

    /**
     * @param User $user
     * @param Transaction $model
     * @param $input
     * @return Transaction
     */
    public function update(User $user, Transaction $model, $input)
    {
        $model->fill($input);
        $model->updated_by = $user->id;
        $model->save();
        return $model;
    }
}
