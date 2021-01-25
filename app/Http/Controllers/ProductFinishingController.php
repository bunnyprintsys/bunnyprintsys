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

    // create product finishing
    public function createApi(Request $request)
    {
        $input = $request->all();

        if($request->model) {
            $input['finishing_id'] = $request->model['id'];
        }

        $model = $this->productFinishingService->create($input);

        return $this->success(new ProductFinishingResource($model));
    }

    // edit product finishing
    public function editApi(Request $request)
    {
        $input = $request->all();

        if($request->has('id')) {
            $model = $this->productFinishingService->update($input);
        }
        return $this->success(new ProductFinishingResource($model));
    }

    // update product finishing by given id
    public function updateProductFinishingByIdApi($id)
    {
        $model = ProductFinishing::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }

    // get not binded options
    public function getExcludedFinishingByProductId($productId)
    {
        $productFinishingIds = ProductFinishing::where('product_id', $productId)->get('id');

        $input['excluded_id'] = $productFinishingIds;
        $sortBy = [
            'finishing_name' => 'asc'
        ];
        $data = $this->productFinishingService->all($input, $sortBy, $this->getPerPage());

        return $this->success(ProductFinishingResource::collection($data));
    }
}
