<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatusResource;
use App\Services\StatusService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class StatusController extends Controller
{
    use Pagination;

    private $statusService;

    // middleware auth
    public function __construct(StatusService $statusService)
    {
        $this->middleware('auth');
        $this->statusService = $statusService;
    }

    // return states api
    public function getAllStatusesApi(Request $request)
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
            $data = $this->statusService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(StatusResource::collection($data));
            }
            StatusResource::collection($data);
            // dd($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    // store single status api
    public function storeStatusApi(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();
        $model = $this->statusService->create($input);
        return $this->success(new StatusResource($model));
    }

    // delete single entry api
    public function deleteSingleStatus($id)
    {
        $input['id'] = $id;
        $this->statusService->delete($input);
    }
}
