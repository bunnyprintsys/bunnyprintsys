<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Services\StateService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class StateController extends Controller
{
    use Pagination;

    private $stateService;

    // middleware auth
    public function __construct(StateService $stateService)
    {
        $this->middleware('auth');
        $this->stateService = $stateService;
    }

    // return states api
    public function getStatesApi(Request $request)
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
            $data = $this->stateService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(StateResource::collection($data));
            }
            StateResource::collection($data);
            // dd($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    // return states api by country id
    public function getStatesByCountryId(Request $request, $countryId)
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
        $data = $this->stateService->all($input, $sortBy, $this->getPerPage(), $countryId);
        if ($this->isWithoutPagination()) {
            return $this->success(StateResource::collection($data));
        }
        StateResource::collection($data);
        // dd($data);
        return $this->success($data);
    }
}
