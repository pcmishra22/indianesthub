<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Display the chatbot for leads page.
     */
    public function index()
    {
        // For now, just return a view with empty chatbot data
        $messages = [];
        return view('frontend.chatbot', compact('messages'));
    }
}
