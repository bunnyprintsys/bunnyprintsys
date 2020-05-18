<?php

namespace App\Services;

use App\Models\Multiplier;
use App\Models\User;
use App\Repositories\MultiplierRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class MultiplierService
{

    private $multiplierRepository;
    private $userRepository;

    public function __construct(MultiplierRepository $multiplierRepository, UserRepository $userRepository)
    {
        $this->multiplierRepository = $multiplierRepository;
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
        return $this->multiplierRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Tenant
     * @throws \Exception
     */
    public function createNewMultiplier(User $user, $input)
    {
        $this->validateMandatoryFields($input);
        // remove null value
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $data = $this->multiplierRepository->create($user, $input);

        return $data;
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Tenant
     * @throws \Exception
     */
    public function updateMultiplier(User $user, $input)
    {
        if (!isset($input['id']) || !$input['id']) {
            throw new \Exception('ID must defined', 404);
        }
        $model = $this->getOneById($user, $input['id']);
        if (!$model) {
            throw new \Exception('Member not found', 404);
        }

        $data = $this->multiplierRepository->update($user, $model, $input);

        return $data;
    }

    /**
     * @param User $user
     * @param $id
     * @return Tenant
     */
    public function getOneById(User $user, $id)
    {
/*
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        } */
        $filter['id'] = $id;

        return $this->multiplierRepository->getOne($filter);
    }

    /**
     * @param $input
     * @throws \Exception
     */
    protected function validateMandatoryFields($input)
    {
        $mandatory = ['value'];
        foreach ($mandatory as $value) {
            if (!Arr::get($input, $value, false)) {
                throw new \Exception($value . ' is mandatory');
            }
        }
    }
}
