<?php

namespace App\Services;


use App\Repositories\ProductMaterialRepository;
use App\Repositories\ProductRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\UserRepository;
use App\Traits\HasMultiplierType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ProductMaterialService
{
    use HasMultiplierType;

    private $productRepository;
    private $productMaterialRepository;
    private $materialRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, ProductMaterialRepository $productMaterialRepository, MaterialRepository $materialRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->productMaterialRepository = $productMaterialRepository;
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
        return $this->productMaterialRepository->all($filter, $sortBy, $pagination);
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
        return $this->productMaterialRepository->getOne($filter);
    }

    // get one by filter
    public function getOneByFilter($input)
    {
        $filter = $input;
        $model = $this->productMaterialRepository->getOne($filter);
        // dd($model->get()->toArray());
        return $model;
    }

    // create product material
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
            $material = $this->materialRepository->create($input);
            $input['material_id'] = $material->id;
            $model = $this->productMaterialRepository->create($input);
        }else {
            $input['type_data'] = $input['type'];
            $input['type'] = null;
            $model = $this->getOneByFilter($input);
            // dd($input, $model);
        }

        $input['type'] = $input['type_data'];
        $input['type_data'] = null;
        $this->createMultiplierWithType($model, $input);

        return $model;
    }

    // update product material
    public function update($input)
    {
        // $type = isset($input['type']) ? $input['type'] : 'customer';
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->productMaterialRepository->update($model, $input);
            // dd($model->toArray(), $input);
            $this->updateMultiplierWithType($model, $input);

            return $model;
        }
    }

    // delete product material
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
