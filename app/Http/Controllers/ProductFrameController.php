<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductFrameResource;
use App\Models\ProductFrame;
use App\Services\ProductFrameService;
use App\Traits\Pagination;

class ProductFrameController extends Controller
{
    use Pagination;
    // retrieve all list

    private $productFrameService;

    public function __construct(ProductFrameService $productFrameService)
    {
        $this->middleware('auth');
        $this->productFrameService = $productFrameService;
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
                'frame_name' => 'asc'
            ];
        }
        $data = $this->productFrameService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ProductFrameResource::collection($data));
        }
        ProductFrameResource::collection($data);
        return $this->success($data);
    }
}
