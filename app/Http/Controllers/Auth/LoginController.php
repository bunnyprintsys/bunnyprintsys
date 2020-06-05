<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Traits\FilterPhoneNumber;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if(is_numeric($request->get('email'))){
            return [
                'phone_number' => $this->filterMalaysiaPhoneNumber($request->get('email'), $request->get('phone_country_code')),
                'password' => $request->get('password'),
                'status' => 1
            ];
        }
        return [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'status' => 1
        ];
    }

    /**
     * @param Request $request
     * @param $user
     */
    protected function authenticated(Request $request, $user)
    {
        $user->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);
    }
}
