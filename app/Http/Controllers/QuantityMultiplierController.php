<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\QuantityMultiplierResource;
use App\Models\QuantityMultiplier;
use App\Services\QuantityMultiplierService;
use App\Traits\Pagination;

class QuantityMultiplierController extends Controller
{
    use Pagination;

    private $quantityMultiplierService;

    public function __construct(QuantityMultiplierService $quantityMultiplierService)
    {
        $this->quantityMultiplierService = $quantityMultiplierService;
    }

    // retrieve all quantitymultipliers list
    public function getAllQuantitymultipliersApi()
    {
        $model = QuantityMultiplier::orderBy('min', 'max')->get();

        return $model;
    }

    // retrieve all deliveries by product id list
    public function getAllQuantitymultipliersByProductIdApi($product_id)
    {
        $model = QuantityMultiplier::leftJoin('products', 'products.id', '=', 'quantity_multipliers.product_id')
            ->where('products.id', $product_id)
            ->select(
                'quantity_multipliers.id', 'quantity_multipliers.min', 'quantity_multipliers.max', 'quantity_multipliers.multiplier'
            )
            ->orderBy('quantity_multipliers.min')
            ->get();

        return $model;
    }

    // update quantitymultiplier by given id
    public function updateQuantitymultiplierByIdApi($id)
    {
        $min = request('min');
        $max = request('max');
        $multiplier = request('multiplier');

        $model = QuantityMultiplier::findOrFail($id);
        if($min) {
            $model->min = $min;
        }
        if($max) {
            $model->max = $max;
        }
        if($multiplier) {
            $model->multiplier = $multiplier;
        }

        $model->save();
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
                'min' => 'asc'
            ];
        }
        $data = $this->quantityMultiplierService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(QuantityMultiplierResource::collection($data));
        }
        QuantityMultiplierResource::collection($data);
        return $this->success($data);
    }

    // create product quantitymultiplier
    public function createApi(Request $request)
    {
        $input = $request->all();

        $model = $this->quantityMultiplierService->create($input);

        return $this->success(new QuantityMultiplierResource($model));
    }

    // edit quantitymultiplier
    public function editApi(Request $request)
    {
        $input = $request->data;

        if($input['id']) {
            $model = $this->quantityMultiplierService->update($input);
        }
        return $this->success(new QuantityMultiplierResource($model));
    }
}
