<?php

namespace App\Services;

use App\Models\ProductShape;
use App\Models\Shape;
use App\Repositories\ProductShapeRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ProductShapeService
{

    private $productShapeRepository;
    private $userRepository;

    public function __construct(ProductShapeRepository $productShapeRepository, UserRepository $userRepository)
    {
        $this->productShapeRepository = $productShapeRepository;
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

        $user = Auth::user();
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        }
        return $this->productShapeRepository->all($filter, $sortBy, $pagination);
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
        return $this->productShapeRepository->getOne($filter);
    }

    // create product shape
    public function create($input)
    {
        DB::beginTransaction();
        $model = $this->productShapeRepository->create($input);
        DB::commit();
        return $model;
    }

    // delete product shape
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
