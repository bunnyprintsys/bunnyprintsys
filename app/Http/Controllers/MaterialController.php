<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MaterialResource;
use App\Models\Material;
use App\Models\ProductMaterial;
use App\Services\MaterialService;
use App\Traits\Pagination;

class MaterialController extends Controller
{
    use Pagination;
    private $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->middleware('auth');
        $this->materialService = $materialService;
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
        $data = $this->materialService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(MaterialResource::collection($data));
        }
        MaterialResource::collection($data);
        return $this->success($data);
    }

    // create material
    public function createApi(Request $request)
    {
        $input = $request->all();

        $material = $this->materialService->create($input);

        return $this->success(new MaterialResource($material));
    }

    // edit material
    public function editApi(Request $request)
    {
        $input = $request->all();

        if($request->has('id')) {
            $product = $this->materialService->update($input);
        }
        return $this->success(new MaterialResource($product));
    }

    // delete single entry api
    public function deleteApi($id)
    {
        $input['id'] = $id;
        $this->materialService->delete($input);
    }

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
            ->leftJoin('materials', 'materials.id', '=', 'product_materials.material_id');

            $materials = $materials->when($product_id, function($query) use ($product_id){
                return $query->where('products.id', $product_id);
            });

            $materials = $materials->select(
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
