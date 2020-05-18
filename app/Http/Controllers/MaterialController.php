<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\ProductMaterial;

class MaterialController extends Controller
{
    // retrieve all materials list
    public function getAllMaterialsApi()
    {
        $materials = Material::orderBy('name')->get();
        return $materials;
    }

    // retrieve all materials by product id list
    public function getAllMaterialsByProductIdApi($product_id)
    {
        $materials = ProductMaterial::leftJoin('products', 'products.id', '=', 'product_materials.product_id')
            ->leftJoin('materials', 'materials.id', '=', 'product_materials.material_id')
            ->where('products.id', $product_id)
            ->select(
                'product_materials.id', 'materials.name', 'product_materials.multiplier'
            )
            ->get();

        return $materials;
    }

    // update material by given id
    public function updateProductMaterialByIdApi($id)
    {
        $model = ProductMaterial::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }
}
