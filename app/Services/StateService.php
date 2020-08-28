<?php

namespace App\Services;

use App\Models\State;
use App\Models\User;
use App\Repositories\StateRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class StateService
{

    private $stateRepository;
    private $userRepository;

    public function __construct(StateRepository $stateRepository, UserRepository $userRepository)
    {
        $this->stateRepository = $stateRepository;
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
        return $this->stateRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param $id
     * @return State
     */
    public function getOneById($id)
    {

        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        }
        $filter['id'] = $id;
        return $this->stateRepository->getOne($filter);
    }

    // param country_id
    // return State
    public function getAllByCountryId($filter = [], $sortBy = [], $pagination = false, $countryId)
    {

        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        }
        $filter['country_id'] = $countryId;
        return $this->stateRepository->all($filter, $sortBy, $pagination);
    }
}
