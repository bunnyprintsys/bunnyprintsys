<?php

namespace App\Repositories;

use App\Models\Frame;
use App\Models\User;
use DB;

class FrameRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return Frame::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {
        $query = Frame::with(['productFrames', 'productFrames.product']);
        $query->select('frames.*');

        if (!empty($filter)) {
            $query->filter($filter);
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy);
        }

        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create model entry
    public function create($input)
    {
        $model = new Frame();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // create model entry
    public function update(Frame $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
