<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductLaminationResource;
use App\Models\ProductLamination;
use App\Services\ProductLaminationService;
use App\Traits\Pagination;
use Illuminate\Support\Facades\Auth;

class ProductLaminationController extends Controller
{
    use Pagination;
    // retrieve all shapes list

    private $productLaminationService;

    public function __construct(ProductLaminationService $productLaminationService)
    {
        $this->middleware('auth');
        $this->productLaminationService = $productLaminationService;
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
                'lamination_name' => 'asc'
            ];
        }
        $data = $this->productLaminationService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ProductLaminationResource::collection($data));
        }
        ProductLaminationResource::collection($data);
        return $this->success($data);
    }

    // create product lamination
    public function createApi(Request $request)
    {
        $input = $request->all();

        if($request->model) {
            $input['lamination_id'] = $request->model['id'];
        }

        $model = $this->productLaminationService->create($input);

        return $this->success(new ProductLaminationResource($model));
    }

    // edit product lamination
    public function editApi(Request $request)
    {
        $input = $request->all();

        if($request->has('id')) {
            $model = $this->productLaminationService->update($input);
        }
        return $this->success(new ProductLaminationResource($model));
    }

    // update product shape by given id
    public function updateProductLaminationByIdApi($id)
    {
        $model = ProductLamination::findOrFail($id);
        $multiplier = request('multiplier');

        $model->multiplier = $multiplier;
        $model->save();
    }

    // get not binded options
    public function getExcludedLaminationByProductId($productId)
    {
        $productLaminationIds = ProductLamination::where('product_id', $productId)->get('id');

        $input['excluded_id'] = $productLaminationIds;
        $sortBy = [
            'lamination_name' => 'asc'
        ];
        $data = $this->productLaminationService->all($input, $sortBy, $this->getPerPage());

        return $this->success(ProductLaminationResource::collection($data));
    }
}
