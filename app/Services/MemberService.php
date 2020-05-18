<?php

namespace App\Services;

use App\Models\Member;
use App\Models\User;
use App\Repositories\MemberRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class MemberService
{

    private $memberRepository;
    private $userRepository;

    public function __construct(MemberRepository $memberRepository, UserRepository $userRepository)
    {
        $this->memberRepository = $memberRepository;
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
        return $this->memberRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Tenant
     * @throws \Exception
     */
    public function createNewMember(User $user, $input)
    {
        $this->validateMandatoryFields($input);
        // remove null value
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $data = $this->memberRepository->create($user, $input);

        $data->user()->create([
            'name' => $input['name'],
            'phone_number' => $input['phone_number'],
            'email' => $input['email']
        ]);

        return $data;
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Tenant
     * @throws \Exception
     */
    public function updateMember(User $user, $input)
    {
        if (!isset($input['id']) || !$input['id']) {
            throw new \Exception('ID must defined', 404);
        }
        $model = $this->getOneById($user, $input['id']);
        if (!$model) {
            throw new \Exception('Member not found', 404);
        }

        $data = $this->memberRepository->update($user, $model, $input);

        $data->user()->update([
            'name' => $input['name'],
            'phone_number' => $input['phone_number'],
            'email' => $input['email']
        ]);

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

        return $this->memberRepository->getOne($filter);
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
