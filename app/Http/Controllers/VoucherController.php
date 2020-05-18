<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherResource;
use App\Services\VoucherService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class VoucherController extends Controller
{
    use Pagination;

    private $voucherService;

    // middleware auth
    public function __construct(VoucherService $voucherService)
    {
        $this->middleware('auth');
        $this->voucherService = $voucherService;
    }

    // return index page
    public function index()
    {
        return view('voucher.index');
    }

    // return vouchers api
    public function getVouchersApi(Request $request)
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
            $data = $this->voucherService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(VoucherResource::collection($data));
            }
            VoucherResource::collection($data);
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
    public function storeUpdateVoucherApi(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'roc' => 'required',
        ]);

        try {
            $input = $request->all();
            /** @var User $user */
            $user = Auth::user();
            if ($request->id) { // update
                $data = $this->voucherService->updateVoucher($user, $input);
            } else { // create
                $data = $this->voucherService->createNewVoucher($user, $input);
            }
            return $this->success(new VoucherResource($data));
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }
}
