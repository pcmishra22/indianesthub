<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = \App\Models\User::all();
        return view('backend.users.index', compact('users'));
    }
    public function show($id) {
        $user = \App\Models\User::findOrFail($id);
        return view('backend.users.show', compact('user'));
    }
    public function destroy($id) { /* delete logic */ return back(); }
    public function toggleStatus($id) { /* block/unblock logic */ return back(); }
}
