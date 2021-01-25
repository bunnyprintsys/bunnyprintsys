<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\DeliveryResource;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\ProductDelivery;
use App\Services\DeliveryService;
use App\Services\ProductDeliveryService;
use App\Services\ProductService;
use App\Traits\Pagination;
use App\Traits\HasProductBinding;

class DeliveryController extends Controller
{
    use Pagination;
    use HasProductBinding;
    private $deliveryService;
    private $productDeliveryService;
    private $productService;

    public function __construct(DeliveryService $deliveryService, ProductDeliveryService $productDeliveryService, ProductService $productService)
    {
        $this->middleware('auth');
        $this->deliveryService = $deliveryService;
        $this->productDeliveryService = $productDeliveryService;
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
        $data = $this->deliveryService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(DeliveryResource::collection($data));
        }
        DeliveryResource::collection($data);
        return $this->success($data);
    }


    // retrieve all deliveries list
    public function getAllDeliveriesApi()
    {
        $deliveries = Delivery::orderBy('name')->get();

        return $deliveries;
    }

    // retrieve all deliveries by product id list
    public function getAllDeliveriesByProductIdApi($product_id)
    {
        $deliveries = ProductDelivery::leftJoin('products', 'products.id', '=', 'product_deliveries.product_id')
            ->leftJoin('deliveries', 'deliveries.id', '=', 'product_deliveries.delivery_id')
            ->where('products.id', $product_id)
            ->select(
                'product_deliveries.id', 'deliveries.name', 'product_deliveries.multiplier'
            )
            ->get();

        return $deliveries;
    }

    // create model
    public function createApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:deliveries'
        ]);

        $input = $request->all();

        $model = $this->deliveryService->create($input);

        return $this->success(new DeliveryResource($model));
    }

    // edit model
    public function updateApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:deliveries,name,'.$request->id
        ]);

        $input = $request->all();

        if($request->has('id')) {
            $model = $this->deliveryService->update($input);
        }
        return $this->success(new DeliveryResource($model));
    }

    // update delivery by given id
    public function updateProductDeliveryByIdApi($id)
    {
        $model = ProductDelivery::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }


    public function bindingProduct(Request $request)
    {
        $delivery = Delivery::findOrFail($request->delivery_id);
        $product = Product::findOrFail($request->product_id);

        $delivery->products()->attach($product);
    }

    public function getProductBindings(Request $request)
    {
        $input = $request->all();
        $className = 'deliveries';
        $model = new Delivery();
        $data = $this->hasProductBindings($input, $model, $className);
        return [
            'binded' => DeliveryResource::collection($data['binded']),
            'unbinded' => DeliveryResource::collection($data['unbinded']),
        ];
    }

    public function getMultiplierBindings(Request $request)
    {
        $input = $request->all();
        $delivery = new Delivery();
        // dd($input);
        $data = $this->hasMultiplierBindings($delivery, $input);

        return [
            'binded' => DeliveryResource::collection($data['binded']),
            'unbinded' => DeliveryResource::collection($data['unbinded']),
            'bindedMultiplier' => DeliveryResource::collection($data['bindedMultiplier']),
            'unbindedMultiplier' => DeliveryResource::collection($data['unbindedMultiplier']),
        ];
    }
}
