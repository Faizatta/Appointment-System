<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{

    public function roleindex()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        $permissions = Permission::all();
        $users = User::all();

        return view('roles.roleindex', compact('roles', 'permissions', 'users'));
    }


    public function createrole()
    {
        $permissions = Permission::all();
        return view('roles.createrole', compact('permissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
        ], [
            'name.unique' => 'Role with this name already exists!',
        ]);

        $role = Role::create(['name' => $request->name]);

        if (!empty($request->permissions)) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.roleindex')
            ->with('success', 'Role created successfully.');
    }

    public function showrole(Role $role)
    {
        $role->load('permissions', 'users');
        return view('roles.showrole', compact('role'));
    }

    public function editrole(Role $role)
    {
        $permissions = Permission::all();
        $role->load('permissions');
        return view('roles.editrole', compact('role', 'permissions'));
    }



    public function updaterole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        $role->update(['name' => $request->name]);

        if (!empty($request->permissions)) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.roleindex')
            ->with('success', 'Role updated successfully.');
    }

public function bulkDelete(Request $request)
{
    $ids = $request->ids;

    try {
        User::whereIn('id', $ids)->delete();
        return response()->json(['success' => 'Users deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error deleting users'], 500);
    }
}
    public function destroyrole(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.roleindex')
            ->with('success', 'Role deleted successfully.');
    }
}
