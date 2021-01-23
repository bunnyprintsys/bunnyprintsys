<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FinishingResource;
use App\Http\Resources\ProductFinishingResource;
use App\Models\Finishing;
use App\Models\Product;
use App\Models\ProductFinishing;
use App\Services\FinishingService;
use App\Services\ProductFinishingService;
use App\Services\ProductService;
use App\Traits\Pagination;
use App\Traits\HasProductBinding;

class FinishingController extends Controller
{
    use Pagination;
    use HasProductBinding;
    private $finishingService;
    private $productFinishingService;
    private $productService;

    public function __construct(FinishingService $finishingService, ProductFinishingService $productFinishingService, ProductService $productService)
    {
        $this->middleware('auth');
        $this->finishingService = $finishingService;
        $this->productFinishingService = $productFinishingService;
        $this->productService = $productService;
    }

    public function getAllApi(Request $request)
    {
        // dd('here');
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
        $data = $this->finishingService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(FinishingResource::collection($data));
        }
        FinishingResource::collection($data);
        return $this->success($data);
    }

    // create model
    public function createApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:finishings'
        ]);

        $input = $request->all();

        $model = $this->finishingService->create($input);

        return $this->success(new FinishingResource($model));
    }

    // edit model
    public function updateApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:finishings,name,'.$request->id
        ]);

        $input = $request->all();

        if($request->has('id')) {
            $model = $this->finishingService->update($input);
        }
        return $this->success(new FinishingResource($model));
    }

    // delete single entry api
    public function deleteApi($id)
    {
        $input['id'] = $id;
        $this->finishingService->delete($input);
    }

    // create finishing and product binding
    public function createProductFinishingByProductIdApi($product_id)
    {
        $input['product_id'] = $product_id;
        $input['finishing_id'] = request('finishing_id');

        $model = $this->productFinishingService->create($input);

        return $this->success(new ProductFinishingResource($model));
    }

    // delete finishing and product binding
    public function deleteProductFinishingByProductIdApi($product_id, Request $request)
    {
        $input['finishing_id'] = $request->finishing_id;
        $input['product_id'] = $product_id;

        $model = $this->productFinishingService->getOneByFilter($input);

        $input['id'] = $model->id;
        $this->productFinishingService->delete($input);
    }

    // get binded finishings by product id
    public function getBindedFinishingByProductId($productId)
    {
        $bindedFinishingId = ProductFinishing::where('product_id', $productId)->get('finishing_id')->toArray();
        // dd($bindedMaterialId);

        $collections = Finishing::bindedProduct($bindedFinishingId)->get();

        return $this->success(FinishingResource::collection($collections));
    }

    // get non binded finishings by product id
    public function getNonBindedFinishingByProductId($productId)
    {
        $bindedFinishingId = ProductFinishing::where('product_id', $productId)->get('finishing_id')->toArray();

        $collections = Finishing::excludeBindedProduct($bindedFinishingId)->get();

        return $this->success(FinishingResource::collection($collections));
    }

    public function bindingProduct(Request $request)
    {
        $finishing = Finishing::findOrFail($request->finishing_id);
        $product = Product::findOrFail($request->product_id);

        $finishing->products()->attach($product);
    }

    public function getProductBindings(Request $request)
    {
        $input = $request->all();
        $className = 'finishings';
        $model = new Finishing();
        $data = $this->hasProductBindings($input, $model, $className);
        return [
            'binded' => FinishingResource::collection($data['binded']),
            'unbinded' => FinishingResource::collection($data['unbinded']),
        ];
    }

    public function getMultiplierBindings(Request $request)
    {
        $input = $request->all();
        $finishing = new Finishing();
        $data = $this->hasMultiplierBindings($finishing, $input);
        return [
            'binded' => FinishingResource::collection($data['binded']),
            'unbinded' => FinishingResource::collection($data['unbinded']),
            'bindedMultiplier' => FinishingResource::collection($data['bindedMultiplier']),
            'unbindedMultiplier' => FinishingResource::collection($data['unbindedMultiplier']),
        ];
    }
}
