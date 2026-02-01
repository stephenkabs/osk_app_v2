<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //  public function __construct()
    //  {
    //     $this->middleware('permission:view user',['only'=> ['index','show']]);
    //     $this->middleware('permission:create user',['only'=> ['create','store']]);
    //     $this->middleware('permission:edit user',['only'=> ['update','edit']]);
    //     $this->middleware('permission:delete user',['only'=> ['destroy']]);

    //  }
   public function index()
{
    $users = \App\Models\User::latest()->paginate(15);
    return view('users.index', compact('users'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id=Auth::user()->id;
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();

        return view ('users.create', compact('roles','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        //     'password' => ['required|string|min:8|max:20',
        // ];

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = auth()->user();
        if ($image = $request->file('image')) {
            $destinationPath = 'users/images';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $profileImage['image'] = "$profileImage";
        }

        $user = User::create([
            'name' => $request->first_name,
            'email' => $request->email,
            'image' => $profileImage,
            'password' => Hash::make($request->password),

        ]);

        $user->syncRoles($request->roles);

        return redirect('user')->with('success', 'Role created successfully!');
    }

    public function approve(User $user)
{
    $user->update([
        'status' => 'active',
    ]);

    return back()->with('success', 'User approved successfully.');
}


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        return view ('users.show', compact('roles','user','userRoles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        return view ('users.edit', compact('roles','user','userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // if ($image = $request->file('image')) {
        //     $destinationPath = 'users/images';
        //     $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
        //     $image->move($destinationPath, $profileImage);
        //     $input['image'] = "$profileImage";
        // }else{
        //     unset($input['image']);
        // }

        $data = [
            'name' => $request->name,
            'email,' => $request->email,
        ];

        if(!empty($request->password)){
            $data += [
            'password'=> Hash::make($request->password),
            ];

        }
        $user->update($data);
        $user->syncRoles($request->roles);

        return redirect('user')->with('success', 'Role created successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success','User deleted successfully');
    }



}
