<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\LaminationResource;
use App\Http\Resources\ProductLaminationResource;
use App\Models\Lamination;
use App\Models\Product;
use App\Models\ProductLamination;
use App\Services\LaminationService;
use App\Services\ProductService;
use App\Services\ProductLaminationService;
use App\Traits\Pagination;
use App\Traits\HasProductBinding;

class LaminationController extends Controller
{
    use Pagination;
    use HasProductBinding;
    private $laminationService;
    private $productService;
    private $shapeService;

    public function __construct(LaminationService $laminationService, ProductLaminationService $productLaminationService, ProductService $productService)
    {
        $this->middleware('auth');
        $this->laminationService = $laminationService;
        $this->productLaminationService = $productLaminationService;
        $this->productService = $productService;
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
        $data = $this->laminationService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(LaminationResource::collection($data));
        }
        LaminationResource::collection($data);
        return $this->success($data);
    }

    // retrieve all laminations by product id list
    public function getAllLaminationsByProductIdApi($product_id)
    {
        $laminations = ProductLamination::leftJoin('products', 'products.id', '=', 'product_laminations.product_id')
            ->leftJoin('laminations', 'laminations.id', '=', 'product_laminations.lamination_id')
            ->where('products.id', $product_id)
            ->select(
                'product_laminations.id', 'laminations.name', 'product_laminations.multiplier'
            )
            ->get();

        return $laminations;
    }

    // create model
    public function createApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:laminations'
        ]);

        $input = $request->all();

        $model = $this->laminationService->create($input);

        return $this->success(new LaminationResource($model));
    }

    // edit model
    public function updateApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:laminations,name,'.$request->id
        ]);

        $input = $request->all();

        if($request->has('id')) {
            $model = $this->laminationService->update($input);
        }
        return $this->success(new LaminationResource($model));
    }

    // delete single entry api
    public function deleteApi($id)
    {
        $input['id'] = $id;
        $this->laminationService->delete($input);
    }

    // update product lamination by given id
    public function updateProductLaminationByIdApi($id)
    {
        $model = ProductLamination::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }

    // create lamination and product binding
    public function createProductLaminationByProductIdApi($product_id)
    {
        $input['product_id'] = $product_id;
        $input['lamination_id'] = request('lamination_id');

        $model = $this->productLaminationService->create($input);

        return $this->success(new ProductLaminationResource($model));
    }

    // delete lamination and product binding
    public function deleteProductLaminationByProductIdApi($product_id, Request $request)
    {
        $input['lamination_id'] = $request->lamination_id;
        $input['product_id'] = $product_id;

        $model = $this->productLaminationService->getOneByFilter($input);

        $input['id'] = $model->id;
        $this->productLaminationService->delete($input);
    }

    // get binded laminations by product id
    public function getBindedLaminationByProductId($productId)
    {
        $bindedLaminationId = ProductLamination::where('product_id', $productId)->get('lamination_id')->toArray();

        $collections = Lamination::bindedProduct($bindedLaminationId)->get();

        return $this->success(LaminationResource::collection($collections));
    }

    // get non binded laminations by product id
    public function getNonBindedLaminationByProductId($productId)
    {
        $bindedLaminationId = ProductLamination::where('product_id', $productId)->get('lamination_id')->toArray();

        $collections = Lamination::excludeBindedProduct($bindedLaminationId)->get();

        return $this->success(LaminationResource::collection($collections));
    }


    public function bindingProduct(Request $request)
    {
        $lamination = Lamination::findOrFail($request->lamination_id);
        $product = Product::findOrFail($request->product_id);

        $lamination->products()->attach($product);
    }

    public function getProductBindings(Request $request)
    {
        $input = $request->all();
        $className = 'laminations';
        $model = new Lamination();
        $data = $this->hasProductBindings($input, $model, $className);
        return [
            'binded' => LaminationResource::collection($data['binded']),
            'unbinded' => LaminationResource::collection($data['unbinded']),
        ];
    }

    public function getMultiplierBindings(Request $request)
    {
        $input = $request->all();
        $lamination = new Lamination();
        $data = $this->hasMultiplierBindings($lamination, $input);
        return [
            'binded' => LaminationResource::collection($data['binded']),
            'unbinded' => LaminationResource::collection($data['unbinded']),
            'bindedMultiplier' => LaminationResource::collection($data['bindedMultiplier']),
            'unbindedMultiplier' => LaminationResource::collection($data['unbindedMultiplier']),
        ];
    }
}
