<?php

namespace App\Services;

use App\Repositories\FinishingRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class FinishingService
{

    private $productRepository;
    private $finishingRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, FinishingRepository $finishingRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->finishingRepository = $finishingRepository;
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
        return $this->finishingRepository->all($filter, $sortBy, $pagination);
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
        return $this->finishingRepository->getOne($filter);
    }

    // create  model
    public function create($input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $model = $this->finishingRepository->create($input);
        return $model;
    }

    // update  model
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->finishingRepository->update($model, $input);
            return $model;
        }
    }

    // delete model
    public function delete($input)
    {
        // dd($input, $input['id']);
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
