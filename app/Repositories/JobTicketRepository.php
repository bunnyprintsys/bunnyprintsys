<?php

namespace App\Repositories;

use App\Models\JobTicket;
use App\Models\User;
use DB;

class JobTicketRepository
{
    /**
     * @param $input
     * @return mixed
     */
    public function getOne($input)
    {
        return JobTicket::filter($input)->first();
    }

    /**
     * @param array $filter
     * @param array $sortBy
     * @param bool $pagination
     * @return mixed
     */
    public function all($filter = [], $sortBy = [], $pagination = false)
    {

        $query = JobTicket::query();
        if (!empty($filter)) {
            $query->filter($filter);
        }
        if (!empty($sortBy)) {
            $query->sortBy($sortBy);
        }
        // dd($query->get());
        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }

    // create entry
    public function create($input)
    {
        $model = new JobTicket();
        $model->fill($input);
        $model->save();
        return $model;
    }

    // create entry
    public function update(JobTicket $model, $input)
    {
        $model->fill($input);
        $model->save();
        return $model;
    }
}
