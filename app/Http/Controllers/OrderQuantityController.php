<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderQuantity;

class OrderQuantityController extends Controller
{
    // retrieve all orderquantities list
    public function getAllOrderquantitiesApi()
    {
        $orderquantities = OrderQuantity::orderBy('qty')->get();
        return $orderquantities;
    }

    // update orderquantity by given id
    public function updateOrderquantityByIdApi($id)
    {
        $name = request('name');
        $qty = request('qty');

        $model = OrderQuantity::findOrFail($id);
        if($name) {
            $model->name = $name;
        }
        if($qty) {
            $model->qty = $qty;
        }

        $model->save();
    }
}
