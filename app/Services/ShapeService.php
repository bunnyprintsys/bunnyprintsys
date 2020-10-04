<?php

namespace App\Services;

use App\Models\Shape;
use App\Repositories\ShapeRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ShapeService
{

    private $shapeRepository;
    private $userRepository;

    public function __construct(ShapeRepository $shapeRepository, UserRepository $userRepository)
    {
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
        return $this->shapeRepository->all($filter, $sortBy, $pagination);
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
        return $this->shapeRepository->getOne($filter);
    }

    // create product shape
    public function create($input)
    {
        DB::beginTransaction();
        $model = $this->shapeRepository->create($input);
        DB::commit();
        return $model;
    }

    // delete product shape
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
