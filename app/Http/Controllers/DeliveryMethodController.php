<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeliveryMethodResource;
use App\Services\DeliveryMethodService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class DeliveryMethodController extends Controller
{
    use Pagination;

    private $deliveryMethodService;

    // middleware auth
    public function __construct(DeliveryMethodService $deliveryMethodService)
    {
        $this->middleware('auth');
        $this->deliveryMethodService = $deliveryMethodService;
    }

    // return states api
    public function getAllDeliveryMethodsApi(Request $request)
    {
        try {
            $input = $request->all();
            $order = $request->get('reverse') == 'true' ? 'asc' : 'desc';
            if (isset($input['sortkey']) && !empty($input['sortkey'])) {
                $sortBy = [
                    $request->get('sortkey') => $order
                ];
            } else {
                $sortBy = [
                    'created_at' => 'asc'
                ];
            }
            $data = $this->deliveryMethodService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(DeliveryMethodResource::collection($data));
            }
            DeliveryMethodResource::collection($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    // store single delivery method api
    public function storeDeliveryMethodApi(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();
        $model = $this->deliveryMethodService->create($input);
        return $this->success(new DeliveryMethodResource($model));
    }

    // delete single entry api
    public function deleteSingleDeliveryMethod($id)
    {
        $input['id'] = $id;
        $this->deliveryMethodService->delete($input);
    }
}
