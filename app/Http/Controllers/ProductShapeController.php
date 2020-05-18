<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\ProductShape;
use App\Services\ProductService;
use App\Services\ProductShapeService;
use App\Models\User;
use DB;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductShapeController extends Controller
{
    use Pagination;


    private $productService;
    private $productShapeService;

    public function __construct(ProductService $productService, ProductShapeService $productShapeService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
        $this->productShapeService = $productShapeService;
    }

    /**
     * retrieve units by given filter
     *
     * @param Request $request
     * @return mixed
     */
    public function getProductShapesApi(Request $request)
    {

        $input = $request->all();
        $order = $request->get('reverse') == 'true' ? 'asc' : 'desc';
        if (isset($input['sortkey']) && !empty($input['sortkey'])) {
            $sortBy = [
                $request->get('sortkey') => $order
            ];
        } else {
            $sortBy = [
                'created_at' => 'desc'
            ];
        }
        $data = $this->productShapeService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ProductShapeResource::collection($data));
        }
        ProductShapeResource::collection($data);
        return $this->success($data);

    }
}
