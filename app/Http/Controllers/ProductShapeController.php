<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductShapeResource;
use App\Models\ProductShape;
use App\Services\ProductShapeService;
use App\Traits\Pagination;
use Illuminate\Support\Facades\Auth;

class ProductShapeController extends Controller
{
    use Pagination;
    // retrieve all shapes list

    private $productShapeService;

    public function __construct(ProductShapeService $productShapeService)
    {
        $this->middleware('auth');
        $this->productShapeService = $productShapeService;
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
                'shape_name' => 'asc'
            ];
        }
        $data = $this->productShapeService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ProductShapeResource::collection($data));
        }
        ProductShapeResource::collection($data);
        return $this->success($data);
    }

    // create product shape
    public function createApi(Request $request)
    {
        $input = $request->all();

        if($request->model) {
            $input['shape_id'] = $request->model['id'];
        }

        $productShape = $this->productShapeService->create($input);

        return $this->success(new ProductShapeResource($productShape));
    }

    // edit product shape
    public function editApi(Request $request)
    {
        $input = $request->all();

        if($request->has('id')) {
            $productShape = $this->productShapeService->update($input);
        }
        return $this->success(new ProductShapeResource($productShape));
    }

    // update product shape by given id
    public function updateProductShapeByIdApi($id)
    {
        $model = ProductShape::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }

    // get not binded options
    public function getExcludedShapeByProductId($productId)
    {
        $productShapeIds = ProductShape::where('product_id', $productId)->get('id');

        $input['excluded_id'] = $productShapeIds;
        $sortBy = [
            'shape_name' => 'asc'
        ];
        $data = $this->productShapeService->all($input, $sortBy, $this->getPerPage());

        return $this->success(ProductShapeResource::collection($data));
    }


}
