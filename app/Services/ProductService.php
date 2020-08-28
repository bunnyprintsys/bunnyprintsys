<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProductService
{

    private $productRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
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

        $user = Auth::user();
        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        }
        return $this->productRepository->all($filter, $sortBy, $pagination);
    }

    /**
     * @param $id
     * @return State
     */
    public function getOneById($id)
    {

        if (!$user->hasRole('super-admin')) {
            $filter['profile_id'] = $user->profile_id;
        }
        $filter['id'] = $id;
        return $this->productRepository->getOne($filter);
    }
}
