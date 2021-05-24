<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;

class RoleController extends Controller
{

    // middleware auth
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return roles api
    public function getAllRolesApi(Request $request)
    {
        $roles = new Role();

        $data = RoleResource::collection($roles->orderBy('name', 'asc')->where('id', '<>', 1)->get());

        return $this->success($data);
    }
}
