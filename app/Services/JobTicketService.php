<?php

namespace App\Services;

use App\Models\JobTicket;
use App\Models\User;
use App\Repositories\JobTicketRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class JobTicketService
{

    private $jobTicketRepository;
    private $userRepository;

    public function __construct(JobTicketRepository $jobTicketRepository, UserRepository $userRepository)
    {
        $this->jobTicketRepository = $jobTicketRepository;
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
        return $this->jobTicketRepository->all($filter, $sortBy, $pagination);
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
        return $this->jobTicketRepository->getOne($filter);
    }

    // create single entry
    public function create(User $user, $input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }
        $input['code'] = $user->profile->generateNextJobCode();
        // dd($input);
        $model = $this->jobTicketRepository->create($input);
        return $model;
    }

    // update single entry
    public function update($input)
    {
        $model = $this->getOneById($input['id']);
        $this->jobTicketRepository->update($model, $input);
        return $model;
    }

    // delete single entry
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
