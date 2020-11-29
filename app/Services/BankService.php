<?php

namespace App\Services;

use App\Repositories\BankRepository;

class BankService
{

    private $bankRepository;

    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        return $this->bankRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param $id
     * @return State
     */
    public function getOneById($id)
    {
        $filter['id'] = $id;
        return $this->bankRepository->getOne($filter);
    }

    // create model
    public function create($input)
    {
        foreach ($input as $key => $value) {
            if (!$value) {
                unset($input[$key]);
            }
        }

        $model = $this->bankRepository->create($input);
        return $model;
    }

    // update model
    public function update($input)
    {
        if($input['id']){
            $model = $this->getOneById($input['id']);
            $model = $this->bankRepository->update($model, $input);
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
