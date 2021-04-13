<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PaymentStatusResource;
use App\Services\PaymentStatusService;
use App\Traits\Pagination;

class PaymentStatusController extends Controller
{
    use Pagination;

    private $paymentTermService;

    public function __construct(PaymentStatusService $paymentStatusService)
    {
        $this->middleware('auth');
        $this->paymentStatusService = $paymentStatusService;
    }

    public function getAllApi(Request $request)
    {
        $input = $request->all();
        $order = $request->get('reverse') == 'true' ? 'asc' : 'desc';
        if (isset($input['sortkey']) && !empty($input['sortkey'])) {
            $sortBy = [
                $request->get('sortkey') => $order
            ];
        } else {
            $sortBy = [
                'name' => 'asc'
            ];
        }
        $data = $this->paymentStatusService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(PaymentStatusResource::collection($data));
        }
        PaymentStatusResource::collection($data);
        return $this->success($data);
    }
}
