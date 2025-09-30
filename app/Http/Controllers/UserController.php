<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with(['roles', 'permissions'])->select('users.*');

            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->implode(', ');
                })
                ->addColumn('permissions', function ($user) {
                    return $user->permissions->pluck('name')->implode(', ');
                })
                ->addColumn('actions', function ($user) {
                    // Hide actions for Admin
                    if ($user->hasRole('Admin')) {
                        return '<span class="text-muted">-</span>';
                    }

                    $roles = implode(',', $user->roles->pluck('name')->toArray());
                    $permissions = implode(',', $user->permissions->pluck('name')->toArray());

                    return '
                        <a href="#" class="editUserBtn"
                           data-id="' . $user->id . '"
                           data-name="' . htmlspecialchars($user->name) . '"
                           data-email="' . htmlspecialchars($user->email) . '"
                           data-roles="' . htmlspecialchars($roles) . '"
                           data-permissions="' . htmlspecialchars($permissions) . '">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="' . route('users.destroy', $user->id) . '" method="POST"
                              class="d-inline delete-user-form">
                            ' . csrf_field() . method_field("DELETE") . '
                            <button type="button" class="deleteUserBtn" data-name="' . htmlspecialchars($user->name) . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $roles = Role::all();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Sync roles
        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        }

        // Sync permissions
        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return back()->with('success', 'User created successfully!');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Sync roles
        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        } else {
            $user->syncRoles([]);
        }

        // Sync permissions
        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]);
        }

        // Return JSON for AJAX request
        if ($request->ajax()) {
            return response()->json(['success' => 'User updated successfully!']);
        }

        return back()->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting admin users
        if ($user->hasRole('Admin')) {
            return back()->with('error', 'Cannot delete admin users!');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        $ids = $request->ids;

        try {
            // Prevent deleting admin users
            $adminUsers = User::whereIn('id', $ids)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Admin');
                })
                ->count();

            if ($adminUsers > 0) {
                return response()->json([
                    'message' => 'Cannot delete admin users!'
                ], 403);
            }

            User::whereIn('id', $ids)->delete();
            return response()->json(['success' => 'Users deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting users: ' . $e->getMessage()], 500);
        }
    }
}
