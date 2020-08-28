<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Services\CountryService;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    private $countryService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CountryService $countryService)
    {
        $this->middleware('auth', ['except' => [
            'getLabelStickerQuotationIndex', 'getCountriesApi'
        ]]);
        $this->countryService = $countryService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    // return label sticker quotation index
    public function getLabelStickerQuotationIndex()
    {
        return view('public.quotation.label-sticker');
    }

    // return public countries api
    public function getCountriesApi()
    {
        $data = $this->countryService->all([], ['name' => 'asc'], false);

        return $this->success(CountryResource::collection($data));
    }
}
