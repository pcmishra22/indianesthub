<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display the chat messaging page.
     */
    public function index()
    {
        // For now, just return a view with empty chat data
        $messages = [];
        return view('frontend.chat.index', compact('messages'));
    }
}
