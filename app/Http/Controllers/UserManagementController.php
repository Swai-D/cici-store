<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::withCount(['users', 'permissions'])->get();
        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);
        $user->syncRoles([$request->role]);
        return redirect()->route('users.index')->with('success', 'User role updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account!');
        }

        // Prevent deleting the last admin
        if ($user->hasRole('Admin') && User::role('Admin')->count() <= 1) {
            return redirect()->route('users.index')->with('error', 'Cannot delete the last admin user!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
