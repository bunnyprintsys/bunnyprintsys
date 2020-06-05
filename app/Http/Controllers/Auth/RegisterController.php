<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Country;
use App\Models\Member;
use App\Models\Customer;
use App\Models\User;
use App\Services\CustomerService;
use App\Services\UserService;
use App\Traits\FilterPhoneNumber;
use App\Traits\RunningNumber;
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

    use FilterPhoneNumber, RegistersUsers, RunningNumber;

    private $customerService, $userService;
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
    public function __construct(CustomerService $customerService, UserService $userService)
    {
        $this->middleware('guest');
        $this->customerService = $customerService;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact' => ['required']
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    // register user via sign up page
    // param Request $request
    public function store(Request $request)
    {
        if($request->is_cooperate == 'true') {
            $request->validate([
                'company_name' => 'required',
                'roc' => 'required',
                'name' => 'required',
                'email' => 'email|required',
                'phone_number' => 'required'
            ]);
        }else {
            $request->validate([
                'name' => 'required',
                'email' => 'email|required|unique:users,email',
                'phone_number' => 'required'
            ]);
        }

        $input = $request->all();
        $input['phone_country_id'] = $request->phone_country_id['id'];

        $latest_otp = $this->getRandomDigits(6);
        $input['latest_otp'] = $latest_otp;
        // $this->smsService->sendSms()

        if ($request->has('id')) { // update
            $obj = $this->customerService->updateCustomer($input);
        } else { // create
            $obj = $this->customerService->createNewCustomer($input);
        }

        return $this->success(new CustomerResource($obj));
    }

    // new signup registration logic
    public function register(Request $request)
    {
        $this->store($request);
    }

    // phone number validation
    public function validatePhoneNumber(Request $request)
    {
        $phone_country_id = $request->phone_country_id['id'];
        $phone_number = $request->phone_number;

        $country = Country::find($phone_country_id);

        if($country) {
            $request->validate([
                'phone_country_id' => 'required',
                'phone_number' => 'required|phone:'.$country->symbol.',mobile'
            ], [
                'phone_number.phone' => 'Please ensure your phone number is correct'
            ]);
        }

    }
}
