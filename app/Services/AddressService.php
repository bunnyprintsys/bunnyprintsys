<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use App\Repositories\AddressRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AddressService
{

    private $addressRepository;
    private $userRepository;

    public function __construct(AddressRepository $addressRepository, UserRepository $userRepository)
    {
        $this->addressRepository = $addressRepository;
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
        return $this->addressRepository->all($filter, $sortBy, $pagination);
    }

    public function getOneByFilter($input)
    {
        return $this->addressRepository->getOne($input);
    }

    /**
     * @param $input
     * @return \App\Models\Address
     * @throws \Exception
     */
    public function createNewAddress(User $user, $input)
    {
        $this->validateMandatoryFields($input);
        // remove null value
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $data = $this->addressRepository->create($user, $input);
        $data->user()->create($input);

        return $data;
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Address
     * @throws \Exception
     */
    public function updateAddress($input)
    {
        if (!isset($input['id']) || !$input['id']) {
            throw new \Exception('ID must defined', 404);
        }
        $model = $this->getOneById($input['id']);
        if (!$model) {
            throw new \Exception('Member not found', 404);
        }

        $data = $this->addressRepository->update($model, $input);

        // $data->user()->update($input);

        return $data;
    }

    /**
     * @param $id
     * @return Address
     */
    public function getOneById($id)
    {
/*
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        } */
        $filter['id'] = $id;
        return $this->addressRepository->getOne($filter);
    }

    // delete address
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }

    /**
     * @param $input
     * @throws \Exception
     */
    protected function validateMandatoryFields($input)
    {
        $mandatory = ['unit', 'postcode', 'country_id'];
        foreach ($mandatory as $value) {
            if (!Arr::get($input, $value, false)) {
                throw new \Exception($value . ' is mandatory');
            }
        }
    }
}
