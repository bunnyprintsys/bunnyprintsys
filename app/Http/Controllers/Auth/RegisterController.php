<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact' => ['required']
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    // register user via sign up page
    // param Request $request
    public function store()
    {
        if(request('is_cooperate') == 'true') {
            request()->validate([
                'company_name' => 'required',
                'roc' => 'required',
                'name' => 'required',
                'email' => 'email|required',
                'phone_number' => 'required'
            ]);
        }else {
            request()->validate([
                'name' => 'required',
                'email' => 'email|required|unique:users,email',
                'phone_number' => 'required'
            ]);
        }

        $member = Member::create([
            'is_company' => request('is_company'),
            'company_name' => request('company_name'),
            'roc' => request('roc'),
            'company_address' => request('company_address'),
            'credit' => request('credit')
        ]);

        $member->user()->create([
            'name' => request('name'),
            'email' => request('email'),
            'phone_number' => request('phone_number')
        ]);
    }

    // new signup registration logic
    public function register(Request $request)
    {
        $this->store($request->all());
    }
}
