<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Services\MemberService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class MemberController extends Controller
{
    use Pagination;

    private $memberService;

    // middleware auth
    public function __construct(MemberService $memberService)
    {
        $this->middleware('auth');
        $this->memberService = $memberService;
    }

    // return index page
    public function index()
    {
        return view('member.index');
    }

    // return members api
    public function getMembersApi(Request $request)
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
            $data = $this->memberService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(MemberResource::collection($data));
            }
            MemberResource::collection($data);
            // dd($data);
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
    public function storeUpdateMemberApi(Request $request)
    {
        if($request->is_company == 'true') {
            $this->validate($request, [
                'name' => 'required',
                'company_name' => 'required',
                'roc' => 'required',
                'phone_number' => 'required',
                'email' => 'required',
            ]);
        }else {
            $this->validate($request, [
                'name' => 'required',
                'phone_number' => 'required',
                'email' => 'required',
            ]);
        }

        try {
            $input = $request->all();
            /** @var User $user */
            $user = Auth::user();
            if ($request->id) { // update
                $member = $this->memberService->updateMember($user, $input);
            } else { // create
                $member = $this->memberService->createNewMember($user, $input);
            }
            return $this->success(new MemberResource($member));
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Resources\Json\Resource
     */
    public function toggleStatusMemberApi($id)
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $member = $this->memberService->updateMember($user, ['id' => $id, 'status' => Member::STATUS_INACTIVE]);
            return $this->success(new TenantResource($tenant));
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }
}
