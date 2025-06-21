<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManagementController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        $allPermissions = Permission::all();
        $assignedPermissions = $role->permissions->pluck('id')->toArray();
        
        return view('roles.show', compact('role', 'allPermissions', 'assignedPermissions'));
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $allPermissions = Permission::all();
        $assignedPermissions = $role->permissions->pluck('id')->toArray();
        
        return view('roles.edit', compact('role', 'allPermissions', 'assignedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $permissionIds = $request->input('permissions', []);
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        
        $role->syncPermissions($permissions);

        return redirect()->route('users.index')->with('success', 'Role permissions updated successfully!');
    }

    public function create()
    {
        $allPermissions = Permission::all();
        return view('roles.create', compact('allPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $permissionIds = $request->input('permissions', []);
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('users.index')->with('success', 'Role created successfully!');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'Admin') {
            return redirect()->route('users.index')->with('error', 'Cannot delete Admin role!');
        }

        $role->delete();
        return redirect()->route('users.index')->with('success', 'Role deleted successfully!');
    }
} 