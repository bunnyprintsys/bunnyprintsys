<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\User;
use App\Repositories\Repository;
use DB;

class AdminRepository
{
    protected $model;

    public function __construct(Admin $model)
    {
        $this->model = $model;
    }


    public function getOne($input)
    {
        return Admin::filter($input)->first();
    }

    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = Admin::query();
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

    public function create(User $user, $input)
    {
        $model = new Admin();
        $model->fill($input);
        // $model->profile_id = $user->profile_id;
        $model->save();
        return $model;
    }

    public function update(User $user, Admin $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
