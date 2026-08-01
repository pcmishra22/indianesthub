<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiChatSession;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    public function index(Request $request)
    {
        $query = AiChatSession::with('property:id,title,slug')
            ->withCount('messages')
            ->latest('last_message_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $stats = [
            'total'          => AiChatSession::count(),
            'lead_captured'  => AiChatSession::where('status', 'lead-captured')->count(),
            'today'          => AiChatSession::whereDate('created_at', today())->count(),
        ];

        $sessions = $query->paginate(20)->withQueryString();

        return view('backend.ai-chat.index', compact('sessions', 'stats'));
    }

    public function show($id)
    {
        $session = AiChatSession::with(['messages' => fn ($q) => $q->orderBy('id'), 'property:id,title,slug'])
            ->findOrFail($id);

        return view('backend.ai-chat.show', compact('session'));
    }

    public function destroy($id)
    {
        AiChatSession::findOrFail($id)->delete();

        return redirect()->route('admin.ai-chat.index')->with('success', 'Chat session deleted successfully.');
    }
}
