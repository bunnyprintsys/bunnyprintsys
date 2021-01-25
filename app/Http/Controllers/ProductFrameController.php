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

    // create product frame
    public function createApi(Request $request)
    {
        $input = $request->all();

        if($request->model) {
            $input['frame_id'] = $request->model['id'];
        }

        $model = $this->productFrameService->create($input);

        return $this->success(new ProductFrameResource($model));
    }

    // edit product frame
    public function editApi(Request $request)
    {
        $input = $request->all();

        if($request->has('id')) {
            $model = $this->productFrameService->update($input);
        }
        return $this->success(new ProductFrameResource($model));
    }

    // update product frame by given id
    public function updateProductFrameByIdApi($id)
    {
        $model = ProductFrame::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }

    // get not binded options
    public function getExcludedFrameByProductId($productId)
    {
        $productFrameIds = ProductFrame::where('product_id', $productId)->get('id');

        $input['excluded_id'] = $productFrameIds;
        $sortBy = [
            'frame_name' => 'asc'
        ];
        $data = $this->productFrameService->all($input, $sortBy, $this->getPerPage());

        return $this->success(ProductFrameResource::collection($data));
    }
}
