<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaxResource;
use App\Services\TaxService;
use App\Traits\Pagination;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    use Pagination;

    private $taxService;

    // middleware auth
    public function __construct(TaxService $taxService)
    {
        $this->middleware('auth');
        $this->taxService = $taxService;
    }

    // return taxes api
    public function getTaxesApi(Request $request)
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
                    'name' => 'asc'
                ];
            }
            $data = $this->taxService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(TaxResource::collection($data));
            }
            TaxResource::collection($data);
            // dd($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }
}
