<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,author,admin',
        ]);

        $user->update(['role' => $validated['role']]);

        // Sync Spatie role
        $user->syncRoles([$validated['role']]);

        return back()->with('success', "Role updated to {$validated['role']} for {$user->name}.");
    }
}
