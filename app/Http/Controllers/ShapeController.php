<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductShape;
use App\Models\Shape;
use App\Http\Resources\ShapeResource;
use App\Services\ProductService;
use App\Services\ShapeService;
use App\Traits\Pagination;
use Illuminate\Support\Facades\Auth;

class ShapeController extends Controller
{
    use Pagination;
    // retrieve all shapes list

    private $productService;
    private $shapeService;

    public function __construct(ProductService $productService, ShapeService $shapeService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
        $this->shapeService = $shapeService;
    }

    public function getAllShapesApi(Request $request)
    {
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
        $data = $this->shapeService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ShapeResource::collection($data));
        }
        ShapeResource::collection($data);
        return $this->success($data);

        return $shapes;
    }

    // retrieve all shapes by product id list
    public function getAllShapesByProductIdApi(Request $request)
    {
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
        $input['product_id'] = $product_id;
        $data = $this->countryService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ShapeResource::collection($data));
        }
        ShapeResource::collection($data);
        return $this->success($data);



        $shapes = ProductShape::leftJoin('products', 'products.id', '=', 'product_shapes.product_id')
            ->leftJoin('shapes', 'shapes.id', '=', 'product_shapes.shape_id')
            ->where('products.id', $product_id)
            ->select(
                'product_shapes.id', 'shapes.name', 'product_shapes.multiplier'
            )
            ->get();

        return $shapes;
    }

    // update product shape by given id
    public function updateProductShapeByIdApi($id)
    {
        $model = ProductShape::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }
/*
    // store single shape api
    public function storeSalesChannelsApi(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();
        $model = $this->salesChannelService->create($input);
        return $this->success(new SalesChannelResource($model));
    }

    // delete single entry api
    public function deleteSingleSalesChannel($id)
    {
        $input['id'] = $id;
        $this->salesChannelService->delete($input);
    } */

}
