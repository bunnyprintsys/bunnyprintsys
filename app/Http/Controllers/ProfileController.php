<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class ProfileController extends Controller
{
    use Pagination;

    private $profileService;

    // middleware auth
    public function __construct(ProfileService $profileService)
    {
        $this->middleware('auth');
        $this->profileService = $profileService;
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
    public function storeUpdateProfileApi(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'roc' => 'required',
        ]);

        try {
            $input = $request->all();
            /** @var User $user */
            $user = Auth::user();
            if ($request->id) { // update
                $data = $this->profileService->updateProfile($user, $input);
            } else { // create
                $data = $this->profileService->createNewProfile($user, $input);
            }
            return $this->success(new ProfileResource($data));
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }
}
