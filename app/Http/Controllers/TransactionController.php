<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class TransactionController extends Controller
{
    use Pagination;
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->middleware('auth');
        $this->transactionService = $transactionService;
    }

    // return transaction page
    public function index()
    {
        return view('transaction.index');
    }

    // return transactions api
    public function getTransactionsApi(Request $request)
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
            $data = $this->transactionService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(TransactionResource::collection($data));
            }
            TransactionResource::collection($data);
            // dd($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Resources\Json\Resource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeUpdateTransactionApi(Request $request)
    {
        try {
            $input = $request->all();
            /** @var User $user */
            $user = Auth::user();
            if ($request->id) { // update
                $data = $this->transactionService->updateTransaction($user, $input);
            } else { // create
                $data = $this->transactionService->createNewTransaction($user, $input);
            }
            return $this->success(new TransactionResource($data));
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }
}
