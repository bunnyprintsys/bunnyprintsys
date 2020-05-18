<?php

namespace App\Http\Controllers;

use App\Http\Resources\MultiplierResource;
use App\Services\MultiplierService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class MultiplierController extends Controller
{
    use Pagination;

    private $multiplierService;

    // middleware auth
    public function __construct(MultiplierService $multiplierService)
    {
        $this->middleware('auth');
        $this->multiplierService = $multiplierService;
    }

    // return members api
    public function getMultipliersApi(Request $request)
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
                    'created_at' => 'desc'
                ];
            }
            $data = $this->multiplierService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(MultiplierResource::collection($data));
            }
            MultiplierResource::collection($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    // store or update multiplier api
    public function storeUpdateMultiplierApi(Request $request)
    {

        try {
            $input = $request->all();

            $user = Auth::user();
            if ($request->id) { // update
                $multiplier = $this->multiplierService->updateMultiplier($user, $input);
            } else { // create
                $multiplier = $this->multiplierService->createNewMultiplier($user, $input);
            }
            return $this->success(new MultiplierResource($multiplier));
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

}
