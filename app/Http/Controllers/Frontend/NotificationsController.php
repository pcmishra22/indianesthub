<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        // For now, just return a view with empty notifications
        $notifications = [];
        return view('frontend.notifications.index', compact('notifications'));
    }
}
