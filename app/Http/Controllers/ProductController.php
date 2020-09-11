<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class ProductController extends Controller
{
    use Pagination;

    private $productService;

    // middleware auth
    public function __construct(ProductService $productService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
    }

    // return product index
    public function index()
    {
        return view('product.index');
    }

    // return states api
    public function getProductsApi(Request $request)
    {
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
        $data = $this->productService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(ProductResource::collection($data));
        }
        ProductResource::collection($data);
        return $this->success($data);

    }
}
