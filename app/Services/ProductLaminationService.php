<?php

namespace App\Services;


use App\Repositories\ProductLaminationRepository;
use App\Repositories\ProductRepository;
use App\Repositories\LaminationRepository;
use App\Repositories\UserRepository;
use App\Traits\HasMultiplierType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ProductLaminationService
{
    use HasMultiplierType;
    private $productRepository;
    private $productLaminationRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, ProductLaminationRepository $productLaminationRepository, LaminationRepository $laminationRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->productLaminationRepository = $productLaminationRepository;
        $this->laminationRepository = $laminationRepository;
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
        return $this->productLaminationRepository->all($filter, $sortBy, $pagination);
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
        return $this->productLaminationRepository->getOne($filter);
    }

    // get one by filter
    public function getOneByFilter($input)
    {
        $filter = $input;

        return $this->productLaminationRepository->getOne($filter);
    }

    // create product lamination
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
            $lamination = $this->laminationRepository->create($input);
            $input['lamination_id'] = $lamination->id;
            $model = $this->productLaminationRepository->create($input);
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

    // update product lamination
    public function update($input)
    {
        // dd($input);
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->productLaminationRepository->update($model, $input);

            $this->updateMultiplierWithType($model, $input);

            return $model;
        }
    }

    // delete product lamination
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
