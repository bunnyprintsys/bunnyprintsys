<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FrameResource;
use App\Http\Resources\ProductFrameResource;
use App\Models\Frame;
use App\Models\Product;
use App\Models\ProductFrame;
use App\Services\FrameService;
use App\Services\ProductFrameService;
use App\Services\ProductService;
use App\Traits\HasProductBinding;
use App\Traits\Pagination;

class FrameController extends Controller
{
    use Pagination;
    use HasProductBinding;
    private $frameService;
    private $productFrameService;
    private $productService;

    public function __construct(FrameService $frameService, ProductFrameService $productFrameService, ProductService $productService)
    {
        $this->middleware('auth');
        $this->frameService = $frameService;
        $this->productFrameService = $productFrameService;
        $this->productService = $productService;
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
                'name' => 'asc'
            ];
        }
        $data = $this->frameService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(FrameResource::collection($data));
        }
        FrameResource::collection($data);
        return $this->success($data);
    }

    // create model
    public function createApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:frames'
        ]);

        $input = $request->all();

        $model = $this->frameService->create($input);

        return $this->success(new FrameResource($model));
    }

    // edit model
    public function updateApi(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:frames,name,'.$request->id
        ]);

        $input = $request->all();

        if($request->has('id')) {
            $model = $this->frameService->update($input);
        }
        return $this->success(new FrameResource($model));
    }

    // delete single entry api
    public function deleteApi($id)
    {
        $input['id'] = $id;
        $this->frameService->delete($input);
    }

    // create frame and product binding
    public function createProductFrameByProductIdApi($product_id)
    {
        $input['product_id'] = $product_id;
        $input['frame_id'] = request('frame_id');

        $model = $this->productFrameService->create($input);

        return $this->success(new ProductFrameResource($model));
    }

    // delete frame and product binding
    public function deleteProductFrameByProductIdApi($product_id, Request $request)
    {
        $input['frame_id'] = $request->frame_id;
        $input['product_id'] = $product_id;

        $model = $this->productFrameService->getOneByFilter($input);

        $input['id'] = $model->id;
        $this->productFrameService->delete($input);
    }

    // get binded frames by product id
    public function getBindedFrameByProductId($productId)
    {
        $bindedFrameId = ProductFrame::where('product_id', $productId)->get('frame_id')->toArray();
        // dd($bindedMaterialId);

        $collections = Frame::bindedProduct($bindedFrameId)->get();

        return $this->success(FrameResource::collection($collections));
    }

    // get non binded frames by product id
    public function getNonBindedFrameByProductId($productId)
    {
        $bindedFrameId = ProductFrame::where('product_id', $productId)->get('frame_id')->toArray();

        $collections = Frame::excludeBindedProduct($bindedFrameId)->get();

        return $this->success(FrameResource::collection($collections));
    }

    public function bindingProduct(Request $request)
    {
        $frame = Frame::findOrFail($request->frame_id);
        $product = Product::findOrFail($request->product_id);

        $frame->products()->attach($product);
    }

    public function getProductBindings(Request $request)
    {
        $input = $request->all();
        $className = 'frames';
        $model = new Frame();
        $data = $this->hasProductBindings($input, $model, $className);
        return [
            'binded' => FrameResource::collection($data['binded']),
            'unbinded' => FrameResource::collection($data['unbinded']),
        ];
    }

    public function getMultiplierBindings(Request $request)
    {
        $input = $request->all();
        $frame = new Frame();
        $data = $this->hasMultiplierBindings($frame, $input);
        return [
            'binded' => FrameResource::collection($data['binded']),
            'unbinded' => FrameResource::collection($data['unbinded']),
            'bindedMultiplier' => FrameResource::collection($data['bindedMultiplier']),
            'unbindedMultiplier' => FrameResource::collection($data['unbindedMultiplier']),
        ];
    }
}
