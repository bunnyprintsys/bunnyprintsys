<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\DealRepository;
use App\Repositories\ProductRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use PDF;

class TransactionService
{

    private $dealRepository;
    private $productRepository;
    private $transactionRepository;
    private $userRepository;

    public function __construct(DealRepository $dealRepository, ProductRepository $productRepository, TransactionRepository $transactionRepository, UserRepository $userRepository)
    {
        $this->dealRepository = $dealRepository;
        $this->productRepository = $productRepository;
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
/*
        $user = Auth::user();
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        } */
        return $this->transactionRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Transaction
     * @throws \Exception
     */
    public function createNewTransaction(User $user, $input)
    {
        $this->validateMandatoryFields($input);

        DB::beginTransaction();

        $input['job_id'] = $user->profile->generateNextJobId();

        if(isset($input['is_convert_invoice'])) {
            $input['invoice_id'] = $user->profile->generateNextInvoiceId();
        }
        // remove null value
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $data = $this->transactionRepository->create($user, $input);
        DB::commit();

        return $data;
    }

    /**
     * @param User $user
     * @param $input
     * @return \App\Models\Transaction
     * @throws \Exception
     */
    public function updateTransaction(User $user, $input)
    {
        if (!isset($input['id']) || !$input['id']) {
            throw new \Exception('ID must defined', 404);
        }
        $model = $this->getOneById($input['id']);
        if (!$model) {
            throw new \Exception('Member not found', 404);
        }
        if(isset($input['is_convert_invoice']) and $model->invoice_id === null) {
            $input['invoice_id'] = $user->profile->generateNextInvoiceId();
        }

        unset($input['created_by']);
        $data = $this->transactionRepository->update($user, $model, $input);

        return $data;
    }

    /**
     * @param User $user
     * @param $id
     * @return Tenant
     */
    public function getOneById($id)
    {
/*
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        } */
        $filter['id'] = $id;

        return $this->transactionRepository->getOne($filter);
    }

    // bind deals to transaction
    public function addDealsToTransaction(Transaction $transaction, $inputs, $user = null)
    {
        try {
            DB::beginTransaction();

            // if (empty($inputs)) {
            //     throw new \Exception('Items is empty');
            // }

            $total = 0;
            $items = [];
            $transaction->deals()->delete();
            // dd($inputs);
            foreach ($inputs as $index => $input) {
                if (!isset($input['item_id'])) {
                    throw new \Exception('Items.' . $index . ' item_id not found');
                }
                if (!isset($input['qty'])) {
                    throw new \Exception('Items.' . $index . ' qty not found');
                }
                if ($input['qty'] <= 0) {
                    throw new \Exception('Items.' . $index . ' qty must be positive number');
                }
                if ($input['price'] <= 0) {
                    throw new \Exception('Items.' . $index . ' price must be positive number');
                }

                $input['transaction_id'] = $transaction->id;
                $input['product_id'] = $input['item_id'];
                $productInput['id'] = $input['item_id'];

                $product = $this->productRepository->getOne($productInput);
                // dd($input, $product, $input);
                if(!$product->is_material) {
                    $input['material_id'] = null;
                }
                if(!$product->is_shape) {
                    $input['shape_id'] = null;
                }
                if(!$product->is_lamination) {
                    $input['lamination_id'] = null;
                }
                if(!$product->is_frame) {
                    $input['frame_id'] = null;
                }
                if(!$product->is_finishing) {
                    $input['finishing_id'] = null;
                }

                $input['amount'] = $input['qty'] * $input['price'];
                $total += round($input['amount'], 2);
                $items[] = $this->dealRepository->create($user, $input);
            }
            $transaction->subtotal = $total;
            $transaction->grandtotal = $total;
            $transaction->save();
            DB::commit();
            return $items;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // generate invoices pdf
    public function generateInvoicePdf(Transaction $transaction)
    {
            $pdf = PDF::loadView('pdf.simple_invoice', [
                'data' => $transaction
            ]);
            $pdf = $pdf->setPaper('A4');

            $name = $transaction->invoice_id . '_' .time() . '.pdf';

            return $pdf->stream($name);
    }

    /**
     * @param $input
     * @throws \Exception
     */
    protected function validateMandatoryFields($input)
    {
        $mandatory = [];
        foreach ($mandatory as $value) {
            if (!Arr::get($input, $value, false)) {
                throw new \Exception($value . ' is mandatory');
            }
        }
    }
}
