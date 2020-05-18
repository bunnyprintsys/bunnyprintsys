<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\ProductDelivery;

class DeliveryController extends Controller
{
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

    // update delivery by given id
    public function updateProductDeliveryByIdApi($id)
    {
        $model = ProductDelivery::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }
}
