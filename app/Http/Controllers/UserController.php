<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
        'roles' => 'required|array',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $user->syncRoles($request->roles);
    return redirect()->route('roles.roleindex')->with('success', 'User created and role(s) assigned successfully.');

}

public function assignRole(Request $request, User $user)
{
    $request->validate(['roles' => 'required|array']);
    $user->syncRoles($request->roles);
    return redirect()->route('roles.roleindex')->with('success', 'Role(s) assigned successfully.');
}


   public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'roles' => 'required|array', // validate roles array
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    if ($request->roles) {
        $user->syncRoles($request->roles); // use roles array, not 'role'
    }

    return redirect()->back()->with('success', 'User updated successfully.');
}

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('roles.roleindex')->with('success', 'User deleted successfully.');
    }


}
