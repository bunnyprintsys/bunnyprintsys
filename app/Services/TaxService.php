<?php

namespace App\Services;

use App\Models\Tax;
use App\Models\User;
use App\Repositories\TaxRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class TaxService
{

    private $taxRepository;
    private $userRepository;

    public function __construct(TaxRepository $taxRepository, UserRepository $userRepository)
    {
        $this->taxRepository = $taxRepository;
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
        return $this->taxRepository->all($filter, $sortBy, $pagination);
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
        return $this->taxRepository->getOne($filter);
    }
}
