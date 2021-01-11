<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceController extends Controller
{

    // middleware auth
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return index page
    public function index($type = 'customer')
    {
        return view('price.index', compact('type'));
    }
}
