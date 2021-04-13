<?php

namespace App\Services;

use App\Repositories\PaymentStatusRepository;

class PaymentStatusService
{

    private $paymentTermRepository;

    public function __construct(PaymentStatusRepository $paymentStatusRepository)
    {
        $this->paymentStatusRepository = $paymentStatusRepository;
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        return $this->paymentStatusRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param $id
     * @return State
     */
    public function getOneById($id)
    {
        $filter['id'] = $id;
        return $this->paymentStatusRepository->getOne($filter);
    }

    // create model
    public function create($input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $model = $this->paymentStatusRepository->create($input);
        return $model;
    }

    // update model
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->paymentStatusRepository->update($model, $input);
            return $model;
        }
    }

    // delete model
    public function delete($input)
    {
        $model = $this->getOneById($input['id']);
        $model->delete();
    }
}
