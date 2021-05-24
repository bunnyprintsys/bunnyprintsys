<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\User;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use App\Services\OtpService;
use App\Services\UserService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AdminService
{

    private $adminRepository;
    private $userRepository;
    private $otpService;
    private $userService;

    public function __construct(AdminRepository $adminRepository, UserRepository $userRepository, OtpService $otpService, UserService $userService)
    {
        $this->adminRepository = $adminRepository;
        $this->userRepository = $userRepository;
        $this->otpService = $otpService;
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
        return $this->adminRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Admin
     * @throws \Exception
     */
    public function createNewAdmin(User $user, $input)
    {
        $this->validateMandatoryFields($input);
        // remove null value
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }
        $input['profile_id'] = $user->profile_id;
        $data = $this->adminRepository->create($user, $input);

        $inputUser = $input;

        if (empty($inputUser['password'])) {
            unset($inputUser['password']);
        }else {
            $inputUser['password'] = bcrypt($inputUser['password']);
        }

        unset($inputUser['phone_number_country_code']);
        unset($inputUser['role']);
        unset($inputUser['password_confirmation']);

        $userId = $data->user()->create($inputUser);

        $user = $this->userService->getOneById($userId);

        if(isset($input['role'])) {
            $user->roles()->detach();
            $user->assignRole($input['role']['name']);
        }

        return $data;
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Admin
     * @throws \Exception
     */
    public function updateAdmin(User $user, $input)
    {
        if (!isset($input['id']) || !$input['id']) {
            throw new \Exception('ID must defined', 404);
        }
        $model = $this->getOneById($user, $input['id']);
        if (!$model) {
            throw new \Exception('Member not found', 404);
        }

        $data = $this->adminRepository->update($user, $model, $input);

        $inputUser = $input;

        if (empty($inputUser['password'])) {
            unset($inputUser['password']);
        }else {
            $inputUser['password'] = bcrypt($inputUser['password']);
        }
        unset($inputUser['phone_number_country_code']);
        unset($inputUser['role']);
        unset($inputUser['password_confirmation']);

        // dd($inputUser);
        $userId = $data->user()->update($inputUser);

        $user = $this->userService->getOneById($userId);

        if(isset($input['role'])) {
            $user->roles()->detach();
            $user->assignRole($input['role']['name']);
        }

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

        return $this->adminRepository->getOne($filter);
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
