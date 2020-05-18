<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lamination;
use App\Models\ProductLamination;

class LaminationController extends Controller
{
    // retrieve all laminations list
    public function getAllLaminationsApi()
    {
        $laminations = Lamination::orderBy('name')->get();

        return $laminations;
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

    // update product lamination by given id
    public function updateProductLaminationByIdApi($id)
    {
        $model = ProductLamination::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }
}
