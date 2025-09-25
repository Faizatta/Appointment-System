<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\User;
// use Spatie\Permission\Models\Role;
// use Yajra\DataTables\Facades\DataTables;
// use Illuminate\Support\Facades\Hash;

// class UserController extends Controller
// {
//     public function index(Request $request)
//     {
//         if ($request->ajax()) {
//             $users = User::with('roles')->select('users.*');

//             return DataTables::of($users)
//                 ->addColumn('roles', function ($user) {
//                     return $user->roles->pluck('name')->implode(', ');
//                 })
//                 ->addColumn('actions', function ($user) {
//                     // Hide actions for admin
//                     if ($user->hasRole('Admin')) {
//                         return '<span class="text-muted">-</span>';
//                     }
//                     $roles = implode(',', $user->roles->pluck('name')->toArray());

//                     return '
//                         <a href="#" class="btn btn-sm btn-outline-primary editUserBtn"
//                            data-id="' . $user->id . '"
//                            data-name="' . $user->name . '"
//                            data-email="' . $user->email . '"
//                            data-roles="' . $roles . '">
//                             <i class="fas fa-edit"></i>
//                         </a>
//                         <form action="' . route('users.destroy', $user->id) . '" method="POST"
//                               class="d-inline delete-user-form">
//                             ' . csrf_field() . method_field("DELETE") . '
//                             <button type="submit" class="btn btn-sm btn-outline-danger">
//                                 <i class="fas fa-trash"></i>
//                             </button>
//                         </form>
//                     ';
//                 })
//                 ->rawColumns(['actions'])
//                 ->make(true);
//         }

//         $roles = Role::all();
//         return view('roles.index', compact('roles'));
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|email|unique:users,email',
//             'roles' => 'array',
//         ]);

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//            'password' => Hash::make($request->password), // use input password
//         ]);

//         if ($request->roles) {
//             $user->syncRoles($request->roles);
//         }

//         return back()->with('success', 'User created successfully.');
//     }

//     public function update(Request $request, User $user)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|email|unique:users,email,' . $user->id,
//             'roles' => 'array',
//         ]);

//         $user->update([
//             'name' => $request->name,
//             'email' => $request->email,
//         ]);

//         $user->syncRoles($request->roles ?? []);

//         return back()->with('success', 'User updated successfully.');
//     }

//     public function destroy(User $user)
//     {
//         $user->delete();
//         return back()->with('success', 'User deleted successfully.');
//     }
// }


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')->select('users.*');

            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->implode(', ');
                })
                ->addColumn('actions', function ($user) {
                    if ($user->hasRole('Admin')) {
                        return '<span class="text-muted">-</span>';
                    }

                    $roles = implode(',', $user->roles->pluck('name')->toArray());

                    return '
                        <a href="#" class="btn btn-sm btn-outline-primary editUserBtn"
                           data-id="' . $user->id . '"
                           data-name="' . $user->name . '"
                           data-email="' . $user->email . '"
                           data-roles="' . $roles . '">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="' . route('users.destroy', $user->id) . '" method="POST"
                              class="d-inline delete-user-form">
                            ' . csrf_field() . method_field("DELETE") . '
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // password confirmation
            'roles' => 'array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        return back()->with('success', 'User created successfully.');
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles($request->roles ?? []);

        return back()->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
