<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\ProductMaterialResource;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductMaterial;
use App\Services\MaterialService;
use App\Services\ProductMaterialService;
use App\Traits\Pagination;
use App\Traits\HasProductBinding;

class MaterialController extends Controller
{
    use Pagination;
    use HasProductBinding;
    private $materialService;

    public function __construct(MaterialService $materialService, ProductMaterialService $productMaterialService)
    {
        $this->middleware('auth');
        $this->materialService = $materialService;
        $this->productMaterialService = $productMaterialService;
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
        $request->validate([
            'name' => 'required|unique:materials'
        ]);

        $input = $request->all();

        $material = $this->materialService->create($input);

        return $this->success(new MaterialResource($material));
    }

    // edit material
    public function updateApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:materials,name,'.$request->id
        ]);

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

    // create material and product binding
    public function createProductMaterialByProductIdApi($product_id)
    {
        $input['product_id'] = $product_id;
        $input['material_id'] = request('material_id');

        $model = $this->productMaterialService->create($input);

        return $this->success(new ProductMaterialResource($model));
    }

    // delete material and product binding
    public function deleteProductMaterialByProductIdApi($product_id, Request $request)
    {
        $input['material_id'] = $request->material_id;
        $input['product_id'] = $product_id;

        $model = $this->productMaterialService->getOneByFilter($input);

        $input['id'] = $model->id;
        $this->productMaterialService->delete($input);
    }

    // update material by given id
    public function updateProductMaterialByIdApi($id)
    {
        $model = ProductMaterial::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }

    // get binded materials by product id
    public function getBindedMaterialByProductId(Request $request, $productId)
    {
        $input['product_id'] = $productId;
        if($type = $request->type) {
            $input['type'] = $type;
        }
        $bindedMaterialId = $this->productMaterialService->all($input);

        $materialKeys = [];
        if($bindedMaterialId) {
            foreach($bindedMaterialId as $binded) {
                // if()
                array_push($materialKeys, $binded->material_id);
            }
        }

        $materials = Material::bindedProduct($materialKeys)->get();

        return $this->success(MaterialResource::collection($materials));
    }

    // get non binded materials by product id
    public function getNonBindedMaterialByProductId($productId)
    {
        $bindedMaterialId = ProductMaterial::where('product_id', $productId)->get('material_id')->toArray();

        $materials = Material::excludeBindedProduct($bindedMaterialId)->get();

        return $this->success(MaterialResource::collection($materials));
    }

    public function bindingProduct(Request $request)
    {
        $material = Material::findOrFail($request->material_id);
        $product = Product::findOrFail($request->product_id);

        $material->products()->attach($product);
    }

    public function getProductBindings(Request $request)
    {
        $input = $request->all();
        $className = 'materials';
        $model = new Material();
        $data = $this->hasProductBindings($input, $model, 'materials');
        return [
            'binded' => MaterialResource::collection($data['binded']),
            'unbinded' => MaterialResource::collection($data['unbinded']),
        ];
    }

    public function getMultiplierBindings(Request $request)
    {
        $input = $request->all();
        $material = new Material();
        // dd($input);
        $data = $this->hasMultiplierBindings($material, $input);

        return [
            'binded' => MaterialResource::collection($data['binded']),
            'unbinded' => MaterialResource::collection($data['unbinded']),
            'bindedMultiplier' => MaterialResource::collection($data['bindedMultiplier']),
            'unbindedMultiplier' => MaterialResource::collection($data['unbindedMultiplier']),
        ];
    }

}
