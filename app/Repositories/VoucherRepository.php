<?php

namespace App\Repositories;

use App\Models\Voucher;
use App\Models\User;
use DB;

class VoucherRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Voucher::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Voucher::query();
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
        $model = new Voucher();
        $model->fill($input);
        $model->save();
        return $model;
    }

    /**
     * @param User $user
     * @param Voucher $model
     * @param $input
     * @return Voucher
     */
    public function update(User $user, Voucher $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
