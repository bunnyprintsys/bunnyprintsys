<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductShape;
use App\Models\Shape;

class ShapeController extends Controller
{
    // retrieve all shapes list
    public function getAllShapesApi()
    {
        $shapes = Shape::orderBy('name')->get();

        return $shapes;
    }

    // retrieve all shapes by product id list
    public function getAllShapesByProductIdApi($product_id)
    {
        $shapes = ProductShape::leftJoin('products', 'products.id', '=', 'product_shapes.product_id')
            ->leftJoin('shapes', 'shapes.id', '=', 'product_shapes.shape_id')
            ->where('products.id', $product_id)
            ->select(
                'product_shapes.id', 'shapes.name', 'product_shapes.multiplier'
            )
            ->get();

        return $shapes;
    }

    // update product shape by given id
    public function updateProductShapeByIdApi($id)
    {
        $model = ProductShape::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }

}
