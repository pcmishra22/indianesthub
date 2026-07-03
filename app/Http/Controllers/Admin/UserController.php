<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $counts = [
            'active'  => User::where('status', 'active')->count(),
            'blocked' => User::where('status', 'blocked')->count(),
        ];

        return view('backend.users.index', compact('users', 'counts'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('backend.users.show', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        return back()->with('success', "User \"{$name}\" has been deleted.");
    }

    /**
     * Enable / disable a user account. Blocked users are signed out of
     * any active session and can no longer log back in (see UserLoginController).
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = ($user->status === 'blocked') ? 'active' : 'blocked';
        $user->save();

        $label = $user->status === 'active' ? 'Active' : 'Blocked';
        return back()->with('success', "User status updated to {$label}.");
    }
}
