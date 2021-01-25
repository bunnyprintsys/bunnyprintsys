<?php

namespace App\Services;


use App\Repositories\ProductShapeRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ShapeRepository;
use App\Repositories\UserRepository;
use App\Traits\HasMultiplierType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ProductShapeService
{
    use HasMultiplierType;
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
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        if(! isset($input['type'])) {
            $input['type'] = null;
        }

        if(isset($input['name']) && $input['name']) {
            // dd('here1');
            $shape = $this->shapeRepository->create($input);
            $input['shape_id'] = $shape->id;
            $model = $this->productShapeRepository->create($input);
        }else {
            $input['type_data'] = $input['type'];
            $input['type'] = null;
            $model = $this->getOneByFilter($input);

        }
// dd($input, $model->toArray());
        $input['type'] = $input['type_data'];
        $input['type_data'] = null;
        $this->createMultiplierWithType($model, $input);

        return $model;
    }

    // update product shape
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->productShapeRepository->update($model, $input);

            $this->updateMultiplierWithType($model, $input);

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
