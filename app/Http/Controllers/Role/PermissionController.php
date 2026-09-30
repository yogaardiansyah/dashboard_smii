<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view permission', ['only' => ['index']]);
        $this->middleware('permission:create permission', ['only' => ['create','store']]);
        $this->middleware('permission:update permission', ['only' => ['update','edit']]);
        $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Permission::get();
        return view('roleuser.permission.index', ['permissions' => $permissions]);
    }

    public function create()
    {
        return view('roleuser.permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);

        $permission = Permission::create([
            'name' => $request->name
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission Created Successfully',
                'data' => $permission
            ]);
        }

        return redirect('permissions')->with('status','Permission Created Successfully');
    }

    public function edit(Permission $permission)
    {
        return view('roleuser.permission.edit', ['permission' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name,'.$permission->id
            ]
        ]);

        $permission->update([
            'name' => $request->name
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission Updated Successfully',
                'data' => $permission
            ]);
        }

        return redirect('permissions')->with('status','Permission Updated Successfully');
    }

    public function destroy($permissionId)
    {
        $permission = Permission::find($permissionId);
        $permission->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission Deleted Successfully'
            ]);
        }

        return redirect('permissions')->with('status','Permission Deleted Successfully');
    }
}
