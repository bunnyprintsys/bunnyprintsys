<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductMaterialResource;
use App\Models\ProductMaterial;
use App\Services\ProductMaterialService;
use App\Traits\Pagination;
use Illuminate\Support\Facades\Auth;

class ProductMaterialController extends Controller
{
    use Pagination;
    // retrieve all shapes list

    private $productMaterialService;

    public function __construct(ProductMaterialService $productMaterialService)
    {
        $this->middleware('auth');
        $this->productMaterialService = $productMaterialService;
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
                'material_name' => 'asc'
            ];
        }
        $data = $this->productMaterialService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ProductMaterialResource::collection($data));
        }
        ProductMaterialResource::collection($data);
        return $this->success($data);
    }

    // create product material
    public function createApi(Request $request)
    {
        $input = $request->all();

        $model = $this->productMaterialService->create($input);

        return $this->success(new ProductMaterialResource($model));
    }

    // edit product material
    public function editApi(Request $request)
    {
        $input = $request->all();

        if($request->has('id')) {
            $model = $this->productMaterialService->update($input);
        }
        return $this->success(new ProductMaterialResource($model));
    }

    // update product shape by given id
    public function updateProductMaterialByIdApi($id)
    {
        $model = ProductMaterial::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }

    // get not binded options
    public function getExcludedMaterialByProductId($productId)
    {
        $productMaterialIds = ProductMaterial::where('product_id', $productId)->get('id');

        $input['excluded_id'] = $productMaterialIds;
        $sortBy = [
            'material_name' => 'asc'
        ];
        $data = $this->productMaterialService->all($input, $sortBy, $this->getPerPage());

        return $this->success(ProductMaterialResource::collection($data));
    }


}
