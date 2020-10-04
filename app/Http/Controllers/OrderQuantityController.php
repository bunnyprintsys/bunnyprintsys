<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\OrderQuantityResource;
use App\Models\OrderQuantity;
use App\Services\OrderQuantityService;
use App\Traits\Pagination;

class OrderQuantityController extends Controller
{
    use Pagination;

    private $orderQuantityService;

    public function __construct(OrderQuantityService $orderQuantityService)
    {
        $this->orderQuantityService = $orderQuantityService;
    }

    // retrieve all orderquantities list
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
                'qty' => 'asc'
            ];
        }
        $data = $this->orderQuantityService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(OrderQuantityResource::collection($data));
        }
        OrderQuantityResource::collection($data);
        return $this->success($data);
    }

    // create product quantitymultiplier
    public function createApi(Request $request)
    {
        $input = $request->all();

        $model = $this->orderQuantityService->create($input);

        return $this->success(new OrderQuantityResource($model));
    }

    // edit quantitymultiplier
    public function editApi(Request $request)
    {
        $input = $request->all();

        if($input['id']) {
            $model = $this->orderQuantityService->update($input);
        }
        return $this->success(new OrderQuantityResource($model));
    }

    // update orderquantity by given id
    public function updateOrderquantityByIdApi($id)
    {
        $name = request('name');
        $qty = request('qty');

        $model = OrderQuantity::findOrFail($id);
        if($name) {
            $model->name = $name;
        }
        if($qty) {
            $model->qty = $qty;
        }

        $model->save();
    }
}
