<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\AddressResource;
use App\Services\CustomerService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class CustomerController extends Controller
{
    use Pagination;

    private $customerService;

    // middleware auth
    public function __construct(CustomerService $customerService)
    {
        $this->middleware('auth');
        $this->customerService = $customerService;
    }

    // return index page
    public function index()
    {
        return view('customer.index');
    }

    // return customers api
    public function getCustomersApi(Request $request)
    {
        try {
            $input = $request->all();
            $order = $request->get('reverse') == 'true' ? 'asc' : 'desc';
            if (isset($input['sortkey']) && !empty($input['sortkey'])) {
                $sortBy = [
                    $request->get('sortkey') => $order
                ];
            } else {
                $sortBy = [
                    'created_at' => 'desc'
                ];
            }
            $data = $this->customerService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(CustomerResource::collection($data));
            }
            CustomerResource::collection($data);
            // dd($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Resources\Json\Resource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeUpdateCustomerApi(Request $request)
    {
        if($request->is_company == 'true') {
            $this->validate($request, [
                'name' => 'required',
                'company_name' => 'required',
                'roc' => 'required',
                'phone_number' => 'required',
                'email' => 'required',
            ]);
        }else {
            $this->validate($request, [
                'name' => 'required',
                'phone_number' => 'required',
                'email' => 'required',
            ]);
        }

        try {
            $input = $request->all();
            /** @var User $user */
            $user = Auth::user();
            if ($request->id) { // update
                $customer = $this->customerService->updateCustomer($user, $input);
            } else { // create
                $customer = $this->customerService->createNewCustomer($user, $input);
            }
            return $this->success(new CustomerResource($customer));
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    // get addresses by customer id
    public function getAddressApi(Request $request)
    {
        $input = request()->all();
        $customer = $this->customerService->getOneById($input['id']);
        $addresses = AddressResource::collection($customer->addresses);

        return $this->success($addresses);
    }
}
