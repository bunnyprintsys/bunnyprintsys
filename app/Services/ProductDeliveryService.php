<?php

namespace App\Services;


use App\Repositories\ProductDeliveryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\DeliveryRepository;
use App\Traits\HasMultiplierType;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ProductDeliveryService
{
    use HasMultiplierType;
    private $productRepository;
    private $productDeliveryRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, ProductDeliveryRepository $productDeliveryRepository, DeliveryRepository $deliveryRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->productDeliveryRepository = $productDeliveryRepository;
        $this->deliveryRepository = $deliveryRepository;
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
        return $this->productDeliveryRepository->all($filter, $sortBy, $pagination);
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
        return $this->productDeliveryRepository->getOne($filter);
    }

    // create product delivery
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
            $delivery = $this->deliveryRepository->create($input);
            $input['delivery_id'] = $delivery->id;
            $model = $this->productDeliveryRepository->create($input);
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

    // update product delivery
    public function update($input)
    {
        $type = isset($input['type']) ? $input['type'] : 'customer';
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->productDeliveryRepository->update($model, $input);
            if($type === 'customer') {
                $model->customerMultipliers->first()->update($input);
            }
            if($type === 'agent') {
                $model->agentMultipliers->first()->update($input);
            }
            return $model;
        }
    }

    // delete product delivery
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->multipliers()->delete();
        $model->delete();
    }
}
