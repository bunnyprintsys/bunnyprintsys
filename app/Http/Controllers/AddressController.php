<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Services\AddressService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;


class AddressController extends Controller
{
    use Pagination;

    private $addressService;

    // middleware auth
    public function __construct(AddressService $addressService)
    {
        $this->middleware('auth');
        $this->addressService = $addressService;
    }
    // return address api
    public function getAddressApi(Request $request)
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
            $data = $this->addressService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(AddressResource::collection($data));
            }
            AddressResource::collection($data);
            // dd($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    // store or update address
    public function storeUpdateAddressApi(Request $request)
    {
        try {
            $input = $request->all();
            $user = Auth::user();
            if ($request->id) { // update
                $data = $this->addressService->updateAddress($user, $input);
            } else { // create
                $data = $this->addressService->createNewAddress($user, $input);
            }
            return $this->success(new AddressResource($data));
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }
}
