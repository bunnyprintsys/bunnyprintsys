<?php

namespace App\Services;

use App\Models\Lamination;
use App\Repositories\LaminationRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class LaminationService
{

    private $laminationRepository;
    private $userRepository;

    public function __construct(LaminationRepository $laminationRepository, UserRepository $userRepository)
    {
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
        return $this->laminationRepository->all($filter, $sortBy, $pagination);
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
        return $this->laminationRepository->getOne($filter);
    }

    // create product lamination
    public function create($input)
    {
        DB::beginTransaction();
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }
        $model = $this->laminationRepository->create($input);
        DB::commit();
        return $model;
    }

    // update  lamination
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->laminationRepository->update($model, $input);
            return $model;
        }
    }

    // delete product shape
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->multipliers()->delete();
        $model->delete();
    }
}
