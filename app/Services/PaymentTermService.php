<?php

namespace App\Services;

use App\Repositories\PaymentTermRepository;

class PaymentTermService
{

    private $paymentTermRepository;

    public function __construct(PaymentTermRepository $paymentTermRepository)
    {
        $this->paymentTermRepository = $paymentTermRepository;
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        return $this->paymentTermRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param $id
     * @return State
     */
    public function getOneById($id)
    {
        $filter['id'] = $id;
        return $this->paymentTermRepository->getOne($filter);
    }

    // create model
    public function create($input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $model = $this->paymentTermRepository->create($input);
        return $model;
    }

    // update model
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->paymentTermRepository->update($model, $input);
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
