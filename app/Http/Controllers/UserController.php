<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userService;

    // force auth
    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    // return user index page
    public function index()
    {
        return view('user.index');
    }

    // get all users api
    public function getAllUsersApi()
    {
        $users = User::select(
                'users.name', 'users.email', 'users.phone_number', 'users.status', 'users.id', 'users.last_login_at'
            )
            ->filterProfile()
            ->orderBy('users.name')
            ->get();

        return $users;
    }

    // retrieve users by given filter
    public function getUsersApi()
    {
        $perpage = request('perpage');

        $data = User::select(
                'users.name', 'users.email', 'users.phone_number', 'users.status', 'users.id', 'users.last_login_at'
            );

        $data = $this->filterUsersApi($data);

        if($perpage != 'All') {
            $data = $data->paginate($perpage);
        }else {
            $data = $data->get();
        }
        return $data;
    }

    // store or update new individual user
    public function storeUpdateUserApi(UserCreateUpdateRequest $request)
    {
        $user = Auth::user();

        $input = $request->all();

        if (isset($input['id'])) {
            try {
                $user = $this->userService->getOne([
                    'id' => $input['id']
                ]);
                if (!$user) {
                    throw new \Exception('User not found');
                }
                if (empty($input['password'])) {
                    unset($input['password']);
                }

                if (!$user->profile_id && isset($input['profile_id'])) {
                    $this->userService->attachUserRole($user, 'agency');
                }

                $user->fill($input);
                $user->save();

            } catch (\Exception $e) {
                return $this->fail(null, $e->getMessage());
            }
        } else {
            if (!$user->hasRole('super-admin')) {
                return $this->fail(null, 'Forbidden');
            }
            $profile = null;

            if (isset($input['profile_id'])) {
                $profile = $this->profileService->getOneById($input['profile_id']);
            }
            $user =  $this->userService->createNewUser($profile, $input, $profile ? 'agency' : null, false);
        }
        return $this->success(new UserResource($user));

    }

    // toggle single user status api(int user_id)
    public function toggleUserStatusApi($user_id)
    {
        $data = User::findOrFail($user_id);
        $data->status = request('status_code');
        $data->save();
    }

    // return self user account index($user_id)
    public function userAccountIndex()
    {
        return view('user.account');
    }

    // retrieve single user api (int user_id)
    public function getSingleUserApi($user_id)
    {
        $user = User::findOrFail($user_id);

        return $user;
    }

    // return current login user data
    public function getLoginUser()
    {
        $user = Auth::user();
        return $user;
    }

    // users api filter(Query query)
    private function filterUsersApi($query)
    {
        $name = request('name');
        $phone_number = request('phone_number');
        $email = request('email');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($name) {
            $query = $query->where('users.name', 'LIKE', '%'.$name.'%');
        }
        if($phone_number) {
            $query = $query->where('users.phone_number', 'LIKE', '%'.$phone_number.'%');
        }
        if($email) {
            $query = $query->where('users.email', 'LIKE', '%'.$email.'%');
        }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('users.name');
        }

        return $query;
    }
}
