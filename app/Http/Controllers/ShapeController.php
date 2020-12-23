<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductShape;
use App\Models\Shape;
use App\Http\Resources\ProductShapeResource;
use App\Http\Resources\ShapeResource;
use App\Services\ProductShapeService;
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

    public function __construct(ProductShapeService $productShapeService, ProductService $productService, ShapeService $shapeService)
    {
        $this->middleware('auth');
        $this->productShapeService = $productShapeService;
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

    // create model
    public function createApi(Request $request)
    {
        $input = $request->all();

        $model = $this->shapeService->create($input);

        return $this->success(new ShapeResource($model));
    }

    // edit model
    public function updateApi(Request $request)
    {
        $input = $request->all();

        if($request->has('id')) {
            $model = $this->shapeService->update($input);
        }
        return $this->success(new ShapeResource($model));
    }

    // delete single entry api
    public function deleteApi($id)
    {
        $input['id'] = $id;
        $this->shapeService->delete($input);
    }

    // update product shape by given id
    public function updateProductShapeByIdApi($id)
    {
        $model = ProductShape::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }

    // create shape and product binding
    public function createProductShapeByProductIdApi($product_id)
    {
        $input['product_id'] = $product_id;
        $input['shape_id'] = request('shape_id');

        $model = $this->productShapeService->create($input);

        return $this->success(new ProductShapeResource($model));
    }

    // delete shape and product binding
    public function deleteProductShapeByProductIdApi($product_id, Request $request)
    {
        $input['shape_id'] = $request->shape_id;
        $input['product_id'] = $product_id;

        $model = $this->productShapeService->getOneByFilter($input);

        $input['id'] = $model->id;
        $this->productShapeService->delete($input);
    }

    // get binded shapes by product id
    public function getBindedShapeByProductId($productId)
    {
        $bindedShapeId = ProductShape::where('product_id', $productId)->get('shape_id')->toArray();
        // dd($bindedMaterialId);

        $collections = Shape::bindedProduct($bindedShapeId)->get();

        return $this->success(ShapeResource::collection($collections));
    }

    // get non binded shapes by product id
    public function getNonBindedShapeByProductId($productId)
    {
        $bindedShapeId = ProductShape::where('product_id', $productId)->get('shape_id')->toArray();

        $collections = Shape::excludeBindedProduct($bindedShapeId)->get();

        return $this->success(ShapeResource::collection($collections));
    }

}
