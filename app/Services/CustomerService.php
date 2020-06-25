<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CustomerService
{

    private $customerRepository;
    private $userRepository;

    public function __construct(CustomerRepository $customerRepository, UserRepository $userRepository)
    {
        $this->customerRepository = $customerRepository;
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
        return $this->customerRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param $input
     * @return \App\Models\Customer
     * @throws \Exception
     */
    public function createNewCustomer($input)
    {
        $this->validateMandatoryFields($input);
        // remove null value
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $data = $this->customerRepository->create($input);
        $data->user()->create($input);

        return $data;
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Customer
     * @throws \Exception
     */
    public function updateCustomer($input)
    {
        if (!isset($input['id']) || !$input['id']) {
            throw new \Exception('ID must defined', 404);
        }
        $model = $this->getOneById($input['id']);
        if (!$model) {
            throw new \Exception('Member not found', 404);
        }

        $data = $this->customerRepository->update($model, $input);

        $data->user()->update($input);

        return $data;
    }

    /**
     * @param $id
     * @return Tenant
     */
    public function getOneById($id)
    {
/*
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        } */
        $filter['id'] = $id;
        return $this->customerRepository->getOne($filter);
    }

    /**
     * @param $input
     * @throws \Exception
     */
    protected function validateMandatoryFields($input)
    {
        $mandatory = ['name', 'phone_number', 'email'];
        foreach ($mandatory as $value) {
            if (!Arr::get($input, $value, false)) {
                throw new \Exception($value . ' is mandatory');
            }
        }
    }
}
