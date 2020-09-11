<?php

namespace App\Services;

use App\Models\DeliveryMethod;
use App\Models\User;
use App\Repositories\DeliveryMethodRepository;
use App\Repositories\UserRepository;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class DeliveryMethodService
{

    private $deliveryMethodRepository;
    private $userRepository;

    public function __construct(DeliveryMethodRepository $deliveryMethodRepository, UserRepository $userRepository)
    {
        $this->deliveryMethodRepository = $deliveryMethodRepository;
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
        return $this->deliveryMethodRepository->all($filter, $sortBy, $pagination);
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
        return $this->deliveryMethodRepository->getOne($filter);
    }

    // create delivery method
    public function create($input)
    {
        DB::beginTransaction();
        $model = $this->deliveryMethodRepository->create($input);
        DB::commit();
        return $model;
    }

    // delete delivery method
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }

}
