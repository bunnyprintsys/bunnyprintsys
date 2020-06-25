<?php
/**
 * Created by PhpStorm.
 * User: sltee
 * Date: 2019-07-29
 * Time: 15:44
 */

namespace App\Services;

use App\Models\Country;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Class ProfileService
 * @package App\Services
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    // private $emailService;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        // $this->emailService = $emailService;
    }

    /**
     * @param array $filter
     * @return User
     */
    public function getOne($filter = [])
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        }
        return $this->userRepository->getOne($filter);
    }

    // get one by id
    public function getOneById($id)
    {
        $filter['id'] = $id;
        return $this->userRepository->getOne($filter);
    }

    // get one by phone number
    public function getOneByPhoneNumber($phone_country_id, $phone_number)
    {
        $filter['phone_country_id'] = $phone_country_id;
        $filter['phone_number'] = $phone_number;

        return $this->userRepository->getOne($filter);
    }
    /**
     * @param User $user
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return User[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll(User $user, $filter = [], $sortBy = [], $pagination = false)
    {
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        }
        return $this->userRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param Profile $profile
     * @param $input
     * @param $role
     * @param bool $sendEmail
     * @return User
     * @throws \Exception
     */
    public function createNewUser($profile, $input, $role, $sendEmail = false)
    {
        DB::beginTransaction();
        try {
            $mandatory = ['name', 'email', 'phone_number'];
            foreach ($mandatory as $value) {
                if (!Arr::get($input, $value, false)) {
                    throw new \Exception($value . ' is mandatory');
                }
            }

            $exists = ['email', 'phone_number'];

            foreach ($exists as $exist) {
                if ($this->userRepository->isExisted($exist, $input[$exist])) {
                    throw new \Exception($exist . ' already taken', 422);
                }
            }

            if (isset($input['password'])) {
                $password = $input['password'];
            } else {
                $password = substr(str_shuffle(MD5(microtime())), 0, 6);
                $input['is_temporary_password'] = 1;
            }
            $input['password'] = $password;
            $input['status'] = 1;

            if ($profile) {
                $input['profile_id'] = $profile->id;
            }
            $model = $this->userRepository->create($input);

            if ($role) {
                $this->attachUserRole($model, $role);
            }

            DB::commit();
/*
            if ($sendEmail) {
                $this->emailService->sendEmail('Happy Rent Login Credential', $model, EmailLog::TEMPLATE_LOGIN_CREDENTIAL,
                    $profile, null, null, ['password' => $password, 'user_id' => $model->id]);
            } */
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // update user password
    public function updateUser($input)
    {
        if (!isset($input['id']) || !$input['id']) {
            throw new \Exception('ID must defined', 404);
        }
        $model = $this->getOneById($input['id']);
        if (!$model) {
            throw new \Exception('User not found', 404);
        }

        $data = $this->userRepository->update($model, $input);

        return $data;
    }

    /**
     * @param $user
     * @param $role
     * @return mixed
     * @throws \Exception
     */
    public function attachUserRole(User $user, $role)
    {
        if ($user->hasRole($role)) {
            return $user;
        }
        $roleModel = Role::where('name', $role)->first();
        if (!$roleModel) {
            throw new \Exception('Role name not found', 422);
        }
        $user->attachRole($roleModel);
        return $user;
    }
}
