<?php

namespace App\Services;

use App\Models\Status;
use App\Models\User;
use App\Repositories\StatusRepository;
use App\Repositories\UserRepository;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class StatusService
{

    private $statusRepository;
    private $userRepository;

    public function __construct(StatusRepository $statusRepository, UserRepository $userRepository)
    {
        $this->statusRepository = $statusRepository;
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
        return $this->statusRepository->all($filter, $sortBy, $pagination);
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
        return $this->statusRepository->getOne($filter);
    }

    // create status
    public function create($input)
    {
        DB::beginTransaction();
        $model = $this->statusRepository->create($input);
        DB::commit();
        return $model;
    }

    // delete status
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
