<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Services\BankService;
use App\Traits\Pagination;

class BankController extends Controller
{
    use Pagination;

    private $bankService;

    public function __construct(BankService $bankService)
    {
        $this->middleware('auth');
        $this->bankService = $bankService;
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
        $data = $this->bankService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(BankResource::collection($data));
        }
        BankResource::collection($data);
        return $this->success($data);
    }
}
