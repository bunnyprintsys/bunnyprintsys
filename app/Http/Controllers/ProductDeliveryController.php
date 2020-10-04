<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductDeliveryResource;
use App\Models\ProductDelivery;
use App\Services\ProductDeliveryService;
use App\Traits\Pagination;
use Illuminate\Support\Facades\Auth;

class ProductDeliveryController extends Controller
{
    use Pagination;
    // retrieve all shapes list

    private $productDeliveryService;

    public function __construct(ProductDeliveryService $productDeliveryService)
    {
        $this->middleware('auth');
        $this->productDeliveryService = $productDeliveryService;
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
                'delivery_name' => 'asc'
            ];
        }
        $data = $this->productDeliveryService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ProductDeliveryResource::collection($data));
        }
        ProductDeliveryResource::collection($data);
        return $this->success($data);
    }

    // create product delivery
    public function createApi(Request $request)
    {
        $input = $request->all();

        $model = $this->productDeliveryService->create($input);

        return $this->success(new ProductDeliveryResource($model));
    }

    // edit product delivery
    public function editApi(Request $request)
    {
        $input = $request->all();

        if($request->has('id')) {
            $model = $this->productDeliveryService->update($input);
        }
        return $this->success(new ProductDeliveryResource($model));
    }

    // update product delivery by given id
    public function updateProductDeliveryByIdApi($id)
    {
        $model = ProductDelivery::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }


}
