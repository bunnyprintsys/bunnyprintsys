<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductFinishingResource;
use App\Models\ProductFinishing;
use App\Services\ProductFinishingService;
use App\Traits\Pagination;

class ProductFinishingController extends Controller
{
    use Pagination;
    // retrieve all list

    private $productFinishingService;

    public function __construct(ProductFinishingService $productFinishingService)
    {
        $this->middleware('auth');
        $this->productFinishingService = $productFinishingService;
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
                'finishing_name' => 'asc'
            ];
        }
        $data = $this->productFinishingService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ProductFinishingResource::collection($data));
        }
        ProductFinishingResource::collection($data);
        return $this->success($data);
    }
}
