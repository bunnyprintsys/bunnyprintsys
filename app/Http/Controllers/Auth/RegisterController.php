<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\UserResource;
use App\Models\Country;
use App\Models\Member;
use App\Models\Customer;
use App\Models\User;
use App\Services\CustomerService;
use App\Services\OtpService;
use App\Services\SmsService;
use App\Services\UserService;
use App\Traits\FilterPhoneNumber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\App;
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

    use FilterPhoneNumber, RegistersUsers;

    private $customerService, $otpService, $smsService, $userService;
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
    public function __construct(CustomerService $customerService, OtpService $otpService, SmsService $smsService, UserService $userService)
    {
        $this->middleware('guest');
        $this->customerService = $customerService;
        $this->otpService = $otpService;
        $this->smsService = $smsService;
        $this->userService = $userService;
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
/*
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact' => ['required'] */
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    // register user via sign up page
    // param Request $request
    public function store()
    {
        $input = request()->all();
        $input['phone_country_id'] = request('phone_country_id')['id'];
        $obj = $this->customerService->createNewCustomer($input);
        Auth::login($obj->user);

        return $this->success(new CustomerResource($obj));
    }

    public function createOtp()
    {
        $phone_country_code = request('phone_country_id')['code'];
        $phone_number = request('phone_number');

        $otp = $this->otpService->createRandom(5);
        $this->otpService->storeSession($otp);

        $this->smsService->sendByISMS(
            $phone_country_code.$phone_number,
            'Please enter your OTP: '.$otp
        );
    }

    public function validateOtp()
    {
        if($this->otpService->validateSession(request('otp'))) {
            return response()->json([
                'status' => 'success',
                'msg' => 'OTP entered correct',
            ], 200);
        }else {
            return response()->json([
                'status' => 'danger',
                'msg' => 'Incorrect OTP, please try again',
            ], 422);
        }
    }

    // new signup registration logic
    public function register(Request $request)
    {
        $this->store($request);
    }

    // phone number validation
    public function validatePhoneNumber()
    {
        $phone_country_id = request('phone_country_id')['id'];
        $country = Country::find($phone_country_id);

        request()->validate([
            'phone_country_id' => 'required',
            'phone_number' => 'required|phone:'.$country->symbol.',mobile'
        ], [
            'phone_number.phone' => 'Please ensure your phone number is correct'
        ]);
    }

    // validate step 1 applicant info
    public function validateApplicantInfo()
    {
        if(request('is_company') == 'true') {
            return request()->validate([
                'company_name' => 'required',
                'roc' => 'required',
                'name' => 'required',
                'email' => 'email|required|unique:users,email'
            ]);
        }else {
            return request()->validate([
                'name' => 'required',
                'email' => 'email|required|unique:users,email'
            ]);
        }
    }

    // validate step 3 applicant password
    public function validateApplicantPassword()
    {
        return request()->validate([
            'password' => 'required|confirmed',
        ]);
    }

    // validate user phone number
    public function validateUserPhoneNumber()
    {
        $this->validatePhoneNumber();

        if($this->userService->getOneByPhoneNumber(request('phone_country_id')['id'], request('phone_number'))) {
            return response()->json([
                'status' => 'success',
                'msg' => 'User validated, please press "Send OTP" button',
            ], 200);
        }else {
            // return response('User not found, please make sure the phone number is registered before', 422);
            return response()->json([
                'status' => 'danger',
                'msg' => 'User not found, please make sure the phone number is registered before',
            ], 422);
        }
    }

    // reset password
    public function updateUserPassword()
    {
        $input = request()->all();
        $input['phone_country_id'] = request('phone_country_id')['id'];
        $input['id'] = $this->userService->getOneByPhoneNumber(request('phone_country_id')['id'], request('phone_number'))->id;
        $obj = $this->userService->updateUser($input);
        Auth::login($obj);

        return $this->success(new UserResource($obj));
    }
}
