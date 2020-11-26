<?php

namespace App\Services;

use App\Repositories\MaterialRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class MaterialService
{

    private $productRepository;
    private $materialRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, MaterialRepository $materialRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->materialRepository = $materialRepository;
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
        return $this->materialRepository->all($filter, $sortBy, $pagination);
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
        return $this->materialRepository->getOne($filter);
    }

    // create  material
    public function create($input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $model = $this->materialRepository->create($input);
        return $model;
    }

    // update  material
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->materialRepository->update($model, $input);
            return $model;
        }
    }

    // delete product material
    public function delete($input)
    {
        // dd($input, $input['id']);
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
