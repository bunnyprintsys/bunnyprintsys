<?php

namespace App\Repositories;

abstract class Repository
{
    public function getOne($input)
    {
        return $this->model->filter($input)->first();
    }

    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = $this->model->query();
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
/*
    public function create($input)
    {
        $model = new Member();
        $model->fill($input);
        // $model->profile_id = $user->profile_id;
        $model->save();
        return $model;
    } */

    public function update($input)
    {
        $model = $this->model->fill($input);
        $this->model->save();
        return $model;
    }
}