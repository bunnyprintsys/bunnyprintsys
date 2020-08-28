<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Services\CountryService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class CountryController extends Controller
{
    use Pagination;


    private $countryService;

    // middleware auth
    public function __construct(CountryService $countryService)
    {
        $this->middleware('auth');
        $this->countryService = $countryService;
    }

    // return countries api
    public function getAllCountriesApi(Request $request)
    {
        $input = $request->all();
        $order = $request->get('reverse') == 'true' ? 'asc' : 'desc';
        if (isset($input['sortkey']) && !empty($input['sortkey'])) {
            $sortBy = [
                $request->get('sortkey') => $order
            ];
        } else {
            $sortBy = [
                'created_at' => 'desc'
            ];
        }
        $data = $this->countryService->all($input, $sortBy, $this->getPerPage());
        if ($this->isWithoutPagination()) {
            return $this->success(CountryResource::collection($data));
        }
        CountryResource::collection($data);
        return $this->success($data);
    }
}
