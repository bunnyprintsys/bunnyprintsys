<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use App\Repositories\AddressRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Services\AddressService;
use App\Services\UserService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

class ProfileService
{

    private $profileRepository;
    private $addressRepository;
    private $userRepository;
    private $addressService;
    private $userService;

    public function __construct(
        ProfileRepository $profileRepository,
        AddressRepository $addressRepository,
        UserRepository $userRepository,
        AddressService $addressService,
        UserService $userService
        )
    {
        $this->addressRepository = $addressRepository;
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
        $this->addressService = $addressService;
        $this->userService = $userService;
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
        return $this->profileRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Customer
     * @throws \Exception
     */
    public function createNewProfile(User $user, $input)
    {
        DB::beginTransaction();

        $this->validateMandatoryFields($input);
        // remove null value
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }
        $companyInput = $input;
        $companyInput['name'] = $input['company_name'];

        $profile = $this->profileRepository->create($user, $companyInput);

        if($input['unit']) {
            $profile->address()->create($input);
        }

        if($input['name']) {
            $input['phone_country_id'] = $input['country_id'];
            $profile->user()->create($input);
        }

        DB::commit();

        return $profile;
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Customer
     * @throws \Exception
     */
    public function updateProfile(User $user, $input)
    {
        if (!isset($input['id']) || !$input['id']) {
            throw new \Exception('ID not found', 404);
        }
        $profileModel = $this->getOneById($user, $input['id']);
        if (!$profileModel) {
            throw new \Exception('User not found', 404);
        }

        $profileInput = $input;
        $profileInput['name'] = $input['company_name'];
        unset($profileInput['user_id']);
        $profile = $this->profileRepository->update($user, $profileModel, $profileInput);

        $addressInput = $input;
        $addressInput['id'] = $addressInput['address_id'];
        if(isset($addressInput['id']) || $addressInput['id']) {
            if($addressModel = $this->addressService->getOneById($addressInput['id'])) {
                $this->addressRepository->update($user, $addressModel, $addressInput);
            }
        }

        $userInput = $input;
        $userInput['id'] = $userInput['user_id'];
        if(isset($userInput['id']) || $userInput['id']) {
            if($userModel = $this->userService->getOneById($userInput['id'])) {
                $this->userRepository->update($userModel, $userInput);
            }
        }

        return $profile;
    }

    /**
     * @param User $user
     * @param $id
     * @return Tenant
     */
    public function getOneById(User $user, $id)
    {
        $filter['id'] = $id;

        return $this->profileRepository->getOne($filter);
    }

    /**
     * @param $input
     * @throws \Exception
     */
    protected function validateMandatoryFields($input)
    {
        $mandatory = ['company_name', 'job_prefix', 'invoice_prefix'];
        foreach ($mandatory as $value) {
            if (!Arr::get($input, $value, false)) {
                throw new \Exception($value . ' is mandatory');
            }
        }
    }
}
