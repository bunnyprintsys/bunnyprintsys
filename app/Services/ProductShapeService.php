<?php

namespace App\Services;


use App\Repositories\ProductShapeRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ShapeRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ProductShapeService
{

    private $productRepository;
    private $productShapeRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, ProductShapeRepository $productShapeRepository, ShapeRepository $shapeRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->productShapeRepository = $productShapeRepository;
        $this->shapeRepository = $shapeRepository;
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

    // get one by filter
    public function getOneByFilter($input)
    {
        $filter = $input;

        return $this->productShapeRepository->getOne($filter);
    }

    // create product shape
    public function create($input)
    {
        // dd($input);
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        if(isset($input['name']) && $input['name']) {
            $shape = $this->shapeRepository->create($input);
            $input['shape_id'] = $shape->id;
        }

        $model = $this->productShapeRepository->create($input);

        $type = isset($input['type']) ? $input['type'] : 'customer';

        if($type === 'customer') {
            $input['multiplier_type_id'] = 1;
        }
        if($type === 'agent') {
            $input['multiplier_type_id'] = 2;
        }
        $model->multipliers()->create($input);

        return $model;
    }

    // update product shape
    public function update($input)
    {
        $type = isset($input['type']) ? $input['type'] : 'customer';

        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->productShapeRepository->update($model, $input);
            if($type === 'customer') {
                $model->customerMultipliers->first()->update($input);
            }
            if($type === 'agent') {
                $model->agentMultipliers->first()->update($input);
            }
            return $model;
        }
    }

    // delete product shape
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
