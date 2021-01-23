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
        // dd($input, $sortBy, $this->getPerPage());
        $data = $this->productService->all($input, $sortBy, $this->getPerPage());
// dd($data->toArray());
        if ($this->isWithoutPagination()) {
            return $this->success(ProductResource::collection($data));
        }
        ProductResource::collection($data);
        return $this->success($data);
    }

    // create product
    public function createApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products'
        ]);

        $input = $request->all();

        $product = $this->productService->create($input);

        return $this->success(new ProductResource($product));
    }

    // edit product
    public function updateApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products,name,'.$request->id
        ]);
        // dd($request->all())
        $input = $request->all();

        if($request->has('id')) {
            $product = $this->productService->update($input);
        }
        return $this->success(new ProductResource($product));
    }

    // delete single entry api
    public function deleteApi($id)
    {
        $input['id'] = $id;
        $this->productService->delete($input);
    }
}
