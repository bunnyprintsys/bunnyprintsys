<?php

namespace App\Services;

use App\Models\Country;
use App\Models\User;
use App\Repositories\CountryRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CountryService
{

    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
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
        return $this->countryRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Country
     * @throws \Exception
     */
    public function createNewCountry(User $user, $input)
    {
        $this->validateMandatoryFields($input);
        // remove null value
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $data = $this->countryRepository->create($user, $input);

        return $data;
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Country
     * @throws \Exception
     */
    public function updateCountry(User $user, $input)
    {
        if (!isset($input['id']) || !$input['id']) {
            throw new \Exception('ID must defined', 404);
        }
        $model = $this->getOneById($user, $input['id']);
        if (!$model) {
            throw new \Exception('Country not found', 404);
        }

        $data = $this->countryRepository->update($user, $model, $input);

        return $data;
    }

    /**
     * @param User $user
     * @param $id
     * @return Tenant
     */
    public function getOneById(User $user, $id)
    {
        $filter['id'] = $id;

        return $this->countryRepository->getOne($filter);
    }

    /**
     * @param $input
     * @throws \Exception
     */
    protected function validateMandatoryFields($input)
    {
        $mandatory = ['name'];
        foreach ($mandatory as $value) {
            if (!Arr::get($input, $value, false)) {
                throw new \Exception($value . ' is mandatory');
            }
        }
    }
}
