<?php

namespace App\Services;

use App\Models\SalesChannel;
use App\Models\User;
use App\Repositories\SalesChannelRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class SalesChannelService
{

    private $salesChannelRepository;
    private $userRepository;

    public function __construct(SalesChannelRepository $salesChannelRepository, UserRepository $userRepository)
    {
        $this->salesChannelRepository = $salesChannelRepository;
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
        return $this->salesChannelRepository->all($filter, $sortBy, $pagination);
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
        return $this->salesChannelRepository->getOne($filter);
    }

    // create sales channel
    public function create($input)
    {
        DB::beginTransaction();
        $model = $this->salesChannelRepository->create($input);
        DB::commit();
        return $model;
    }

    // delete sales channel
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
