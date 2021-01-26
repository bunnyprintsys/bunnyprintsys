<?php

namespace App\Services;


use App\Repositories\QuantityMultiplierRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class QuantityMultiplierService
{

    private $quantityMultiplierRepository;
    private $userRepository;

    public function __construct(QuantityMultiplierRepository $quantityMultiplierRepository, UserRepository $userRepository)
    {
        $this->quantityMultiplierRepository = $quantityMultiplierRepository;
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
        return $this->quantityMultiplierRepository->all($filter, $sortBy, $pagination);
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
        return $this->quantityMultiplierRepository->getOne($filter);
    }

    // get one by filter
    public function getOneByFilter($input)
    {
        $filter = $input;
        // dd($input);
        $model = $this->quantityMultiplierRepository->getOne($filter);
        return $model;
    }

    // create QuantityMultiplier
    public function create($input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $model = $this->quantityMultiplierRepository->create($input);

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

    // update QuantityMultiplier
    public function update($input)
    {
        $type = isset($input['type']) ? $input['type'] : 'customer';
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->quantityMultiplierRepository->update($model, $input);
            if($type === 'customer') {
                $model->customerMultipliers->first()->update($input);
            }
            if($type === 'agent') {
                $model->agentMultipliers->first()->update($input);
            }
            return $model;
        }
    }

    // delete QuantityMultiplier
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->multipliers()->delete();
        $model->delete();
    }
}
