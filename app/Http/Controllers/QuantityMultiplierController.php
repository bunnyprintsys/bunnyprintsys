<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuantityMultiplier;

class QuantityMultiplierController extends Controller
{
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
}
