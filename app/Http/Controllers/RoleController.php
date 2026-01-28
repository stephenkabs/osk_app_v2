<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //  public function __construct()
    //  {
    //     $this->middleware('permission:view role',['only'=> ['index','show']]);
    //     $this->middleware('permission:create role',['only'=> ['create','store','addPermissionToRole','givePermissionToRole']]);
    //     $this->middleware('permission:edit role',['only'=> ['update','edit']]);
    //     $this->middleware('permission:delete role',['only'=> ['destroy']]);

    //  }
    public function index()
    {
        $id=Auth::user()->id;
        $user = User::find($id);
        $roles = Role::all();
        return view('role.index',compact('roles','user'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id=Auth::user()->id;
        $user = User::find($id);
        return view('role.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = new role();
        $post->name = $request->input('name');
        $post->save();
        return redirect('role')->with('status', 'Role created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $id=Auth::user()->id;
        $user = User::find($id);
        return view ('role.show', compact('role','user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $id=Auth::user()->id;
        $user = User::find($id);
        return view ('role.edit', compact('role','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $input = $request->all();

        $role->update($input);

        return redirect()->route('role.index')->with('status','Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('role.index')->with('status','Role deleted successfully');
    }

    public function addPermissionToRole($roleId)
    {
        // $id=Auth::user()->id;
        $profileData = User::find($roleId);
        $permission = Permission::all();
        $role = Role::findOrFail($roleId);

        $rolePermission = DB::table('role_has_permissions')
        ->where('role_has_permissions.role_id', $roleId)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();

        return view ('role.add-permission', compact('role','permission','rolePermission'));
    }

    public function givePermissionToRole(Request $request, $roleId)
    {


        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);

        return redirect()->back()->with('status','Permission added successfully');
    }
}
