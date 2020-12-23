<?php

namespace App\Services;


use App\Repositories\ProductFrameRepository;
use App\Repositories\ProductRepository;
use App\Repositories\FrameRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ProductFrameService
{

    private $productRepository;
    private $productFrameRepository;
    private $frameRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, ProductFrameRepository $productFrameRepository, FrameRepository $frameRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->productFrameRepository = $productFrameRepository;
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
        return $this->productFrameRepository->all($filter, $sortBy, $pagination);
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
        return $this->productFrameRepository->getOne($filter);
    }

    // get one by filter
    public function getOneByFilter($input)
    {
        $filter = $input;

        return $this->productFrameRepository->getOne($filter);
    }      

    // create product material
    public function create($input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        if(isset($input['name']) && $input['name']) {
            $frame = $this->frameRepository->create($input);
            $input['frame_id'] = $frame->id;
        }        

        $model = $this->productFrameRepository->create($input);
        return $model;
    }

    // update model
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->productFrameRepository->update($model, $input);
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
