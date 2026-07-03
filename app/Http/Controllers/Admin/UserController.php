<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) {
        $query = \App\Models\User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        return view('backend.users.index', compact('users'));
    }
    public function show($id) {
        $user = \App\Models\User::findOrFail($id);
        return view('backend.users.show', compact('user'));
    }
    public function destroy($id) {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
    public function toggleStatus($id) {
        $user = \App\Models\User::findOrFail($id);
        $user->status = ($user->status === 'blocked') ? 'active' : 'blocked';
        $user->save();

        $label = ucfirst($user->status);
        return back()->with('success', "User status updated to {$label}.");
    }
}
