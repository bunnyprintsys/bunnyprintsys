<?php

namespace App\Services;


use App\Repositories\OrderQuantityRepository;
use App\Repositories\UserRepository;
use DB;

class OrderQuantityService
{

    private $orderQuantityRepository;
    private $userRepository;

    public function __construct(OrderQuantityRepository $orderQuantityRepository, UserRepository $userRepository)
    {
        $this->orderQuantityRepository = $orderQuantityRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
/*
        $user = Auth::user();
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        } */
        return $this->orderQuantityRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param $id
     * @return State
     */
    public function getOneById($id)
    {
/*
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        } */
        $filter['id'] = $id;
        return $this->orderQuantityRepository->getOne($filter);
    }

    // create OrderQuantity
    public function create($input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $model = $this->orderQuantityRepository->create($input);
        return $model;
    }

    // update OrderQuantity
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->orderQuantityRepository->update($model, $input);
            return $model;
        }
    }

    // delete OrderQuantity
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
