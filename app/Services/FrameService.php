<?php

namespace App\Services;

use App\Repositories\FrameRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class FrameService
{

    private $productRepository;
    private $frameRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, FrameRepository $frameRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->frameRepository = $frameRepository;
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
        return $this->frameRepository->all($filter, $sortBy, $pagination);
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
        return $this->frameRepository->getOne($filter);
    }

    // create  model
    public function create($input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $model = $this->frameRepository->create($input);
        return $model;
    }

    // update  model
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->frameRepository->update($model, $input);
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
