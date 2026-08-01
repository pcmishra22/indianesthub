<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AiChatSession;
use App\Models\Property;
use App\Services\AiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    /**
     * Full-page chat view (kept for the existing /chatbot route).
     */
    public function index()
    {
        return view('frontend.chatbot');
    }

    /**
     * Handle an incoming chat message from the widget (AJAX).
     */
    public function send(Request $request, AiChatService $ai)
    {
        $request->validate([
            'message'       => 'required|string|max:1000',
            'session_token' => 'required|string|max:64',
            'property_id'   => 'nullable|integer',
        ]);

        $session = AiChatSession::firstOrCreate(
            ['session_token' => $request->session_token],
            [
                'property_id' => $request->property_id,
                'source_page' => url()->previous(),
                'ip_address'  => $request->ip(),
                'user_agent'  => substr((string) $request->userAgent(), 0, 255),
            ]
        );

        $userMessage = trim($request->message);

        $session->messages()->create(['role' => 'user', 'content' => $userMessage]);

        // Opportunistically capture lead details if the visitor shares them.
        $this->extractAndStoreLeadInfo($session, $userMessage);

        // Build property context if this chat started on / relates to a listing.
        $propertyContext = null;
        $propertyId = $request->property_id ?? $session->property_id;
        if ($propertyId) {
            $property = Property::find($propertyId);
            if ($property) {
                $propertyContext = sprintf(
                    "%s — %s in %s, listed for %s (%s). %s",
                    $property->title,
                    $property->bhk_type ?? $property->property_type,
                    $property->city,
                    $property->price ? '₹' . number_format((float) $property->price) : 'price on request',
                    $property->listing_type === 'rent' ? 'for rent' : 'for sale',
                    Str::limit(strip_tags((string) $property->description), 200)
                );
            }
        }

        // For general questions (not tied to one listing), ground the reply in a
        // few real matching properties so the AI doesn't invent listings/prices.
        if (!$propertyContext) {
            $matches = $ai->findMatchingProperties($userMessage);
            if ($matches->isNotEmpty()) {
                $propertyContext = "A few currently listed properties that may match this query (use only if relevant, don't force it):\n"
                    . $matches->map(function ($p) {
                        return sprintf(
                            '- %s (%s, %s) — %s',
                            $p->title,
                            $p->bhk_type ?? '',
                            $p->city,
                            $p->price ? '₹' . number_format((float) $p->price) : 'price on request'
                        );
                    })->implode("\n");
            }
        }

        // Last 10 messages for context (keeps API calls fast and cheap).
        $history = $session->messages()
            ->latest()
            ->take(10)
            ->get()
            ->sortBy('id')
            ->map(fn ($m) => ['role' => $m->role, 'content' => $m->content])
            ->values()
            ->all();

        $result = $ai->reply($history, $propertyContext);

        $session->messages()->create(['role' => 'assistant', 'content' => $result['reply']]);
        $session->update(['last_message_at' => now()]);

        return response()->json([
            'reply' => $result['reply'],
            'error' => $result['error'],
        ]);
    }

    protected function extractAndStoreLeadInfo(AiChatSession $session, string $message): void
    {
        $updates = [];

        if (empty($session->phone) && preg_match('/(?:\+?91[\-\s]?)?([6-9]\d{9})\b/', $message, $m)) {
            $updates['phone'] = $m[1];
        }

        if (empty($session->email) && preg_match('/[\w.+-]+@[\w-]+\.[a-zA-Z]{2,}/', $message, $m)) {
            $updates['email'] = $m[0];
        }

        if ($updates) {
            $updates['status'] = 'lead-captured';
            $session->update($updates);
        }
    }
}
