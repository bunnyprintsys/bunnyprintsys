<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\AddressService;
use App\Services\ProfileService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class ProfileController extends Controller
{
    use Pagination;

    private $addressService;
    private $profileService;

    // middleware auth
    public function __construct(AddressService $addressService, ProfileService $profileService)
    {
        $this->middleware('auth');
        $this->profileService = $profileService;
        $this->addressService = $addressService;
    }

    // return index page
    public function index()
    {
        return view('profile.index');
    }

    // return profiles api
    public function getProfilesApi(Request $request)
    {
        try {
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
            $data = $this->profileService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(ProfileResource::collection($data));
            }
            ProfileResource::collection($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Resources\Json\Resource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeUpdateProfileApi(ProfileRequest $request)
    {
        $input = $request->all();
        // dd($input);
        /** @var User $user */
        $user = Auth::user();
        // dd($input);
        if ($request->id) { // update
            $data = $this->profileService->updateProfile($user, $input);
        } else { // create
            $data = $this->profileService->createNewProfile($user, $input);
        }

        unset($input['user_id']);
        unset($input['company_name']);
        unset($input['roc']);
        unset($input['job_prefix']);
        unset($input['invoice_prefix']);
        unset($input['bank_account_holder']);
        unset($input['bank_account_number']);
        unset($input['bank_id']);

        if($request->unit or $request->road_name or $request->postcode) {
            if($data->address) {
                $data->address()->update($input);
            }else {
                $data->address()->create($input);
            }

        }
        return $this->success(new ProfileResource($data));
    }
}
