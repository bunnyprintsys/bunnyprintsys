<?php

namespace App\Repositories;

use App\Models\Member;
use App\Models\User;
use DB;

class MemberRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Member::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = Member::query();
        if (!empty($filter)) {
            $query->filter($filter);
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy);
        }
        // dd($query->get());
        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    /**
     * @param User $user
     * @param $input
     * @return Member
     */
    public function create(User $user, $input)
    {
        $model = new Member();
        $model->fill($input);
        // $model->profile_id = $user->profile_id;
        $model->save();
        return $model;
    }

    /**
     * @param User $user
     * @param Member $model
     * @param $input
     * @return Member
     */
    public function update(User $user, Member $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
