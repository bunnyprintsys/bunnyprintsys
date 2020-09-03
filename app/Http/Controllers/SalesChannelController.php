<?php

namespace App\Http\Controllers;

use App\Http\Resources\SalesChannelResource;
use App\Services\SalesChannelService;
use App\Traits\Pagination;
use Illuminate\Http\Request;

class SalesChannelController extends Controller
{
    use Pagination;

    private $salesChannelService;

    // middleware auth
    public function __construct(SalesChannelService $salesChannelService)
    {
        $this->middleware('auth');
        $this->salesChannelService = $salesChannelService;
    }

    // return salesChannel api
    public function getAllSalesChannelsApi(Request $request)
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
                    'name' => 'asc'
                ];
            }
            $data = $this->salesChannelService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(SalesChannelResource::collection($data));
            }
            SalesChannelResource::collection($data);
            // dd($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    // delete single entry api
    public function deleteSingleSalesChannel($id)
    {
        $input['id'] = $id;
        $this->salesChannelService->delete($input);
    }
}
