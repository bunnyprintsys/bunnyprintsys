<?php

namespace App\Services;


use App\Repositories\ProductFinishingRepository;
use App\Repositories\ProductRepository;
use App\Repositories\FinishingRepository;
use App\Traits\HasMultiplierType;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ProductFinishingService
{
    use HasMultiplierType;
    private $productRepository;
    private $productFinishingRepository;
    private $finishingRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, ProductFinishingRepository $productFinishingRepository, FinishingRepository $finishingRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->productFinishingRepository = $productFinishingRepository;
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
        return $this->productFinishingRepository->all($filter, $sortBy, $pagination);
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
        return $this->productFinishingRepository->getOne($filter);
    }

    // get one by filter
    public function getOneByFilter($input)
    {
        $filter = $input;

        return $this->productFinishingRepository->getOne($filter);
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
            $finishing = $this->finishingRepository->create($input);
            $input['finishing_id'] = $finishing->id;
            $model = $this->productFinishingRepository->create($input);
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

    // update model
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->productFinishingRepository->update($model, $input);
            return $model;
        }
    }

    // delete model
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
