<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Services\AddressService;
use App\Services\CustomerService;
use App\Services\TransactionService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;
use DB;

class TransactionController extends Controller
{
    use Pagination;
    private $addressService;
    private $customerService;
    private $transactionService;

    public function __construct(
        AddressService $addressService,
        CustomerService $customerService,
        TransactionService $transactionService
        )
    {
        $this->middleware('auth');
        $this->addressService = $addressService;
        $this->customerService = $customerService;
        $this->transactionService = $transactionService;
    }

    // return transaction page
    public function index()
    {
        return view('transaction.index');
    }

    // return transaction data setting page
    public function getDataSettingIndex()
    {
        return view('transaction.data.index');
    }

    // return transactions api
    public function getTransactionsApi(Request $request)
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
            $data = $this->transactionService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(TransactionResource::collection($data));
            }
            TransactionResource::collection($data);
            // dd($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    /**
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Resources\Json\Resource
     */
    public function createTransactionApi(Request $request)
    {
        DB::beginTransaction();

        // current user
        $user = auth()->user();

        // form request
        $customerForm = $request->get('customer_form');
        $transactionForm = $request->get('transaction_form');
        $addressForm = $request->get('address_form');
        $deliveryAddressForm = $request->get('delivery_address_form');
        $billingAddressForm = $request->get('billing_address_form');
        $itemForm = $request->get('item_form');
        $radioOption = $request->get('radio_option');

        // customer management
        if(isset($customerForm['customer']['id'])) {
            $customer = $this->customerService->getOneById($customerForm['customer']['id']);
        }else {
            $customer = $this->customerService->createNewCustomer($customerForm);
        }

        $transactionForm['customer_id'] = $customer->id;
        $transactionForm['sales_channel_id'] = isset($transactionForm['sales_channel']) ? $transactionForm['sales_channel']['id'] : null;
        $transactionForm['status_id'] = isset($transactionForm['status']) ? $transactionForm['status']['id'] : null;
        $transactionForm['is_artwork_provided'] = isset($transactionForm['is_artwork_provided']) ? $transactionForm['is_artwork_provided']['id'] : null;
        $transactionForm['is_design_required'] = isset($transactionForm['is_design_required']) ? $transactionForm['is_design_required']['id'] : null;
        $transactionForm['delivery_method_id'] = isset($transactionForm['delivery_method']) ? $transactionForm['delivery_method']['id'] : null;
        $transactionForm['designed_by'] = isset($transactionForm['designed_by']) ? $transactionForm['designed_by']['id'] : null;

        $transaction = $this->transactionService->createNewTransaction($user, $transactionForm);

        // address management
        // delivery
        $deliveryAddress = '';
        if($radioOption['existingDeliveryAddress'] === 'true') {
            if(isset($deliveryAddressForm['address'])) {
                $deliveryAddress = $this->addressService->getOneById($deliveryAddressForm['address']['id']);
            }
        }else {
            if(isset($deliveryAddressForm['unit']) and isset($deliveryAddressForm['postcode'])) {
                $addressForm['is_delivery'] = 1;
                $deliveryAddress = $customer->addresses()->create($addressForm);
            }
        }
        if($deliveryAddress) {
            $transaction->update(['delivery_address_id' => $deliveryAddress->id]);
        }

        // billing
        if($deliveryAddress and $radioOption['sameBillingDeliveryAddress'] === 'true') {
            $transaction->update(['billing_address_id' => $deliveryAddress->id]);
            $deliveryAddress->update(['is_billing' => 1]);
        } else if($radioOption['sameBillingDeliveryAddress'] === 'false') {
            $billingAddress = '';
            if($radioOption['existingBillingAddress'] === 'true') {
                if(isset($billingAddressForm['address'])) {
                    $billingAddress = $this->addressService->getOneById($billingAddressForm['address']['id']);
                }
            }else {
                if(isset($billingAddressForm['unit']) and isset($billingAddressForm['postcode'])) {
                    $addressForm['is_billing'] = 1;
                    $billingAddress = $customer->addresses()->create($addressForm);
                }
            }
            if($billingAddress) {
                $transaction->update(['billing_address_id' => $billingAddress->id]);
            }
        }

        $items = [];
        foreach ($itemForm['items'] as $item) {
            $data = [
                'item_id' => $item['item']['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'description' => $item['description'],
                'material_id' => isset($item['material']) ? $item['material']['id'] : null,
                'shape_id' => isset($item['shape']) ? $item['shape']['id'] : null,
                'lamination_id' => isset($item['lamination']) ? $item['lamination']['id'] : null,
                'frame_id' => isset($item['frame']) ? $item['frame']['id'] : null,
                'finishing_id' => isset($item['finishing']) ? $item['finishing']['id'] : null,

            ];
            $items[] = $data;
        }
        $this->transactionService->addDealsToTransaction($transaction, $items, $user);
        DB::commit();

        return $this->success(new TransactionResource($transaction));
    }

    // update transaction
    public function updateTransactionApi(Request $request, $transactionId)
    {
        DB::beginTransaction();
        $user = auth()->user();

        // form request
        $customerForm = $request->get('customer_form');
        $transactionForm = $request->get('transaction_form');
        $addressForm = $request->get('address_form');
        $deliveryAddressForm = $request->get('delivery_address_form');
        $billingAddressForm = $request->get('billing_address_form');
        $itemForm = $request->get('item_form');
        $radioOption = $request->get('radio_option');
        // dd($radioOption, $deliveryAddressForm, $billingAddressForm);

        // customer management
        if(isset($customerForm['customer']['id'])) {
            $customer = $this->customerService->getOneById($customerForm['customer']['id']);
        }else {
            $customer = $this->customerService->createNewCustomer($customerForm);
        }

        $transactionForm['id'] = $transactionId;
        $transactionForm['customer_id'] = $customer->id;
        $transactionForm['sales_channel_id'] = isset($transactionForm['sales_channel']) ? $transactionForm['sales_channel']['id'] : null;
        $transactionForm['status_id'] = isset($transactionForm['status']) ? $transactionForm['status']['id'] : null;
        $transactionForm['is_artwork_provided'] = isset($transactionForm['is_artwork_provided']) ? $transactionForm['is_artwork_provided']['id'] : null;
        $transactionForm['is_design_required'] = isset($transactionForm['is_design_required']) ? $transactionForm['is_design_required']['id'] : null;
        $transactionForm['delivery_method_id'] = isset($transactionForm['delivery_method']) ? $transactionForm['delivery_method']['id'] : null;
        $transactionForm['designed_by'] = isset($transactionForm['designed_by']) ? $transactionForm['designed_by']['id'] : null;

        $transaction = $this->transactionService->updateTransaction($user, $transactionForm);

        // address management
        // delivery
        // existing or new
        $deliveryAddress = '';
        if($radioOption['existingDeliveryAddress'] === 'true') {
            if(isset($deliveryAddressForm['address'])) {
                $deliveryAddress = $this->addressService->getOneById($deliveryAddressForm['address']['id']);
            }
        }else {
            if(isset($deliveryAddressForm['unit']) and isset($deliveryAddressForm['postcode'])) {
                $addressForm['is_delivery'] = 1;
                $deliveryAddress = $customer->addresses()->create($addressForm);
            }
        }
        if($deliveryAddress) {
            // $transaction->deliveryAddress()->save($deliveryAddress);
            $transaction->update(['delivery_address_id' => $deliveryAddress->id]);
        }

        // billing
        // same or existing or new
        if($deliveryAddress and $radioOption['sameBillingDeliveryAddress'] === 'true') {
            $transaction->update(['billing_address_id' => $deliveryAddress->id]);
            $deliveryAddress->update(['is_billing' => 1]);
        } else if($radioOption['sameBillingDeliveryAddress'] === 'false') {
            $billingAddress = '';
            if($radioOption['existingBillingAddress'] === 'true') {
                if(isset($billingAddressForm['address'])) {
                    $billingAddress = $this->addressService->getOneById($billingAddressForm['address']['id']);
                }
            }else {
                if(isset($billingAddressForm['unit']) and isset($billingAddressForm['postcode'])) {
                    $addressForm['is_billing'] = 1;
                    $billingAddress = $customer->addresses()->create($addressForm);
                }
            }
            if($billingAddress) {
                $transaction->update(['billing_address_id' => $billingAddress->id]);
            }
        }

        // itemised item management
        $items = [];
        foreach ($itemForm['items'] as $item) {
            // dd($item, $itemForm);
            $data = [
                'item_id' => $item['item_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'description' => $item['description'],
                'material_id' => $item['material_id'],
                'shape_id' => $item['shape_id'],
                'lamination_id' => $item['lamination_id'],
                'frame_id' => $item['frame_id'],
                'finishing_id' => $item['finishing_id']
            ];
            $items[] = $data;
        }
        $this->transactionService->addDealsToTransaction($transaction, $items, $user);
        DB::commit();

        return $this->success(new TransactionResource($transaction));
    }

    // retrieve pdf invoice
    public function getInvoice($transactionId)
    {
        $transaction = $this->transactionService->getOneById($transactionId);

        return $this->transactionService->generateInvoicePdf($transaction);
    }
/*

    public function storeUpdateTransactionApi(Request $request)
    {
        try {
            $input = $request->all();
            $user = Auth::user();
            if ($request->id) { // update
                $data = $this->transactionService->updateTransaction($user, $input);
            } else { // create
                $data = $this->transactionService->createNewTransaction($user, $input);
            }
            return $this->success(new TransactionResource($data));
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    } */
}
