<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $pagination;

    public function setPagination($data)
    {
        $this->pagination = [
            'current_page' => $data->currentPage(),
            'first_page_url' => $data->url(1),
            'from' => $data->firstItem(),
            'last_page' => $data->lastPage(),
            'last_page_url' => $data->url($data->lastPage()),
            'next_page_url' => $data->nextPageUrl(),
            'per_page' => $data->perPage(),
            'prev_page_url' => $data->previousPageUrl(),
            'to' => $data->lastItem(),
            'total' => $data->total(),
        ];
        return $this;
    }

    /**
     * @param null $data
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|Resource
     */
    public function success($data = null, $message = 'Success', $statusCode = 200)
    {
        $result = [
            'success' => true,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        if ($this->pagination) {
            $result['pagination'] = $this->pagination;
        }
        if ($data instanceof LengthAwarePaginator) {
            $result['data'] = $data->items();
            $result['pagination'] = $data->toArray();
            unset($result['pagination']['data']);
        }

        return response()->json($result, $statusCode);
    }

    /**
     * @param null $data
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|Resource
     */
    public function fail($data = null, $message = 'Failed', $statusCode = 412)
    {
        if ($data instanceof Paginator) {
            $data = Resource::collection($data);
        }
        if ($data instanceof Resource) {
            return $data->additional([
                'success' => false,
                'message' => $message
            ]);
        }

        if ($statusCode == 0 || is_string($statusCode)) {
            $statusCode = 500;
        }
        return response()->json([
            'success' => false,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
