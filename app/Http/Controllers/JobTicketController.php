<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobTicketResource;
use App\Imports\JobTicketExcelImport;
use App\Models\ExcelUpload;
use App\Models\JobTicket;
use App\Services\AddressService;
use App\Services\CustomerService;
use App\Services\JobTicketService;
use App\Services\ProductService;
use App\Traits\Pagination;
use Auth;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Storage;



class JobTicketController extends Controller
{
    use Pagination;

    private $customerService;
    private $jobTicketService;
    private $productService;

    // middleware auth
    public function __construct(AddressService $addressService, CustomerService $customerService, JobTicketService $jobTicketService, ProductService $productService)
    {
        $this->middleware('auth');
        $this->addressService = $addressService;
        $this->customerService = $customerService;
        $this->jobTicketService = $jobTicketService;
        $this->productService = $productService;
    }

    // return index page
    public function index()
    {
        return view('job-ticket.index');
    }

    // return all api
    public function getAllApi(Request $request)
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
                    'code' => 'desc'
                ];
            }
            $data = $this->jobTicketService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(JobTicketResource::collection($data));
            }
            JobTicketResource::collection($data);

            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    // create model
    public function createApi(Request $request)
    {
        $request->validate([
            'doc_date' => 'required',
            'qty' => 'required|numeric'
        ]);

        DB::beginTransaction();

        $input = $request->all();
        // dd($input);
        $customerInput = $input;
        $productInput = $input;
        $user = auth()->user();

        // customer management
        if(isset($customerInput['customer'])) {
            $customer = $this->customerService->getOneById($customerInput['customer']['id']);
        }else {
            $customerInput['is_company'] = 1;
            $customerInput['code'] = $customerInput['customer_code'];
            $customerInput['company_name'] = $customerInput['customer_name'];
            $customer = $this->customerService->createNewCustomer($customerInput);
        }

        // product management
        if(isset($productInput['product'])) {
            $product = $this->productService->getOneById($productInput['product']['id']);
        }else {
            $productInput['code'] = $productInput['product_code'];
            $productInput['name'] = $productInput['product_name'];
            $product = $this->productService->create($productInput);
        }

        $input['customer_id'] = $customer->id;
        $input['product_id'] = $product->id;
        if($input['status']) {
            $input['status_id'] = $input['status']['id'];
        }
        $model = $this->jobTicketService->create($user, $input);

        DB::commit();

        return $this->success(new JobTicketResource($model));
    }

    // update single entry
    public function updateApi($id, Request $request)
    {
        $request->validate([
            'doc_date' => 'required',
            'qty' => 'required|numeric'
        ]);
        DB::beginTransaction();

        $input = $request->all();
        // dd($input);
        $customerInput = $input;
        $productInput = $input;
        $addressInput = $input;
        $user = auth()->user();

        // customer management
        if(isset($customerInput['customer'])) {
            $customer = $this->customerService->getOneById($customerInput['customer']['id']);
        }else {
            $customerInput['is_company'] = 1;
            $customerInput['code'] = $customerInput['customer_code'];
            $customerInput['company_name'] = $customerInput['customer_name'];
            $customer = $this->customerService->createNewCustomer($customerInput);
        }

        // product management
        if(isset($productInput['product'])) {
            $product = $this->productService->getOneById($productInput['product']['id']);
        }else {
            $productInput['code'] = $productInput['product_code'];
            $productInput['name'] = $productInput['product_name'];
            $product = $this->productService->create($productInput);
        }

        if(isset($addressInput['address'])) {
            // $address = $this->addressService->getOneById($addressInput['address']['id']);
            $this->addressService->updateAddress($addressInput['address']);
        }

        $input['customer_id'] = $customer->id;
        $input['product_id'] = $product->id;
        if($input['status']) {
            $input['status_id'] = $input['status']['id'];
        }
        $model = $this->jobTicketService->update($input);

        DB::commit();

        return $this->success(new JobTicketResource($model));
    }

    // retrieve 5 latest excels
    public function getFiveLatestExcel()
    {
        $excelUploads = ExcelUpload::latest()->get()->take(5);

        return $excelUploads;
    }


    public function uploadExcel(Request $request)
    {
        try {
            $request->validate([
                'file'  => 'mimes:xls,xlsx'
            ]);

            $file = $request->file('files')[0];
            $name = $file->getClientOriginalName();
            $path = Storage::putFileAs('excels', $file, $name);

            ExcelUpload::create([
                'name' => $name,
                'url' => $path,
                'created_by' => auth()->user()->id
            ]);

            Excel::import(new JobTicketExcelImport, $file);

            return $this->success();
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage(), $e->getCode());
        }
    }

    // delete single address
    public function deleteSingleAddressApi($id)
    {
        $model = $this->addressService->getOneById($id);
        $model->delete();
    }

}
