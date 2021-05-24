<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminResource;
use App\Services\AdminService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    use Pagination;

    private $adminService;

    // middleware auth
    public function __construct(AdminService $adminService)
    {
        $this->middleware('auth');
        $this->adminService = $adminService;
    }

    // return index page
    public function index()
    {
        return view('admin.index');
    }

    // return members api (Request $request)
    public function getAdminsApi(Request $request)
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
            $data = $this->adminService->all($input, $sortBy, $this->getPerPage());
            if ($this->isWithoutPagination()) {
                return $this->success(AdminResource::collection($data));
            }
            AdminResource::collection($data);
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->fail(null, $e->getMessage());
        }
    }

    // update admin api(Request $request)
    public function storeUpdateAdminApi(Request $request)
    {
        // try {
            if($request->password and $request->password_confirmation) {
                $request->validate([
                    'password' => 'required|confirmed',
                ]);
            }

            $input = $request->all();
            // dd($input);
            /** @var User $user */
            $user = Auth::user();
            if ($request->id) { // update
                $admin = $this->adminService->updateAdmin($user, $input);
            } else { // create
                $admin = $this->adminService->createNewAdmin($user, $input);
            }
            return $this->success(new AdminResource($admin));
        // } catch (\Exception $e) {
        //     return $this->fail(null, $e->getMessage());
        // }
    }
}
