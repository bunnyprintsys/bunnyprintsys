<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PaymentTermResource;
use App\Services\PaymentTermService;
use App\Traits\Pagination;

class PaymentTermController extends Controller
{
    use Pagination;

    private $paymentTermService;

    public function __construct(PaymentTermService $paymentTermService)
    {
        $this->middleware('auth');
        $this->paymentTermService = $paymentTermService;
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
        $data = $this->paymentTermService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(PaymentTermResource::collection($data));
        }
        PaymentTermResource::collection($data);
        return $this->success($data);
    }
}
