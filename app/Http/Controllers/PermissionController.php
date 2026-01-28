<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //  public function __construct()
    //  {
    //     $this->middleware('permission:view permission',['only'=> ['index','show']]);
    //     $this->middleware('permission:create permission',['only'=> ['create','store']]);
    //     $this->middleware('permission:edit permission',['only'=> ['update','edit']]);
    //     $this->middleware('permission:delete permission',['only'=> ['destroy']]);

    //  }
    public function index()
    {
        $id=Auth::user()->id;
        $user = User::find($id);
        $permissions = Permission::all();
        return view('permission.index', compact('permissions','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $id=Auth::user()->id;
        // $profileData = User::find($id);
        // return view('permission.create', compact('profileData'));
        return view('permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = new Permission();
        $post->name = $request->input('name');
        $post->save();
        return redirect('permission')->with('status', 'Permission created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        // $id=Auth::user()->id;
        // $profileData = User::find($id);
        return view ('permission.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        // $id=Auth::user()->id;
        // $profileData = User::find($id);
        return view ('permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $input = $request->all();

        $permission->update($input);

        return redirect()->route('permission.index')->with('status','Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permission.index')->with('status','Permission deleted successfully');
    }
}
