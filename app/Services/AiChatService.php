<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.key');
        $this->model  = config('services.gemini.model', 'gemini-2.0-flash');
    }

    public function isConfigured(): bool
    {
        return $this->apiKey !== '';
    }

    /**
     * Get an assistant reply for a conversation.
     *
     * @param array $history  Array of ['role' => 'user'|'assistant', 'content' => string], oldest first.
     * @param string|null $propertyContext  Optional plain-text summary of the property the chat started from.
     * @return array{reply:string, error:bool}
     */
    public function reply(array $history, ?string $propertyContext = null): array
    {
        if (!$this->isConfigured()) {
            return [
                'reply' => "Our AI assistant isn't fully set up yet — please use the WhatsApp or Call button below and our team will help you right away.",
                'error' => true,
            ];
        }

        $systemPrompt = $this->buildSystemPrompt($propertyContext);

        // Gemini uses 'user' / 'model' roles, not 'assistant'.
        $contents = array_map(function ($m) {
            return [
                'role'  => $m['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $m['content']]],
            ];
        }, $history);

        try {
            $response = Http::timeout(20)
                ->retry(2, 300)
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
                    [
                        'system_instruction' => [
                            'parts' => [['text' => $systemPrompt]],
                        ],
                        'contents' => $contents,
                        'generationConfig' => [
                            'temperature'     => 0.6,
                            'maxOutputTokens' => 400,
                        ],
                    ]
                );

            if (!$response->successful()) {
                Log::warning('AiChatService: Gemini API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return [
                    'reply' => "Sorry, I'm having trouble responding right now. Please try again in a moment, or WhatsApp us and we'll help directly.",
                    'error' => true,
                ];
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (!$text) {
                return [
                    'reply' => "I didn't quite catch that — could you rephrase? Or WhatsApp us and our team will help.",
                    'error' => true,
                ];
            }

            return ['reply' => trim($text), 'error' => false];

        } catch (\Throwable $e) {
            Log::error('AiChatService: exception calling Gemini', ['message' => $e->getMessage()]);

            return [
                'reply' => "Sorry, something went wrong on our end. Please try again, or WhatsApp us and we'll help directly.",
                'error' => true,
            ];
        }
    }

    /**
     * Very light intent-based property lookup so the assistant can ground
     * answers in real listings instead of inventing details.
     */
    public function findMatchingProperties(string $userMessage, int $limit = 3)
    {
        $message = strtolower($userMessage);

        $query = Property::query()
            ->where('status', 'Available')
            ->whereIn('listing_status', ['active', 'published'])
            ->latest();

        foreach (['chandigarh', 'mohali', 'zirakpur', 'panchkula', 'kharar'] as $city) {
            if (str_contains($message, $city)) {
                $query->where('city', 'like', "%{$city}%");
                break;
            }
        }

        if (str_contains($message, 'rent')) {
            $query->where('listing_type', 'rent');
        } elseif (str_contains($message, 'buy') || str_contains($message, 'sale') || str_contains($message, 'purchase')) {
            $query->where('listing_type', 'sale');
        }

        foreach ([1, 2, 3, 4] as $bhk) {
            if (str_contains($message, "{$bhk} bhk") || str_contains($message, "{$bhk}bhk")) {
                $query->where('bhk_type', "{$bhk} BHK");
                break;
            }
        }

        return $query->limit($limit)->get(['id', 'title', 'slug', 'city', 'price', 'bhk_type', 'listing_type']);
    }

    protected function buildSystemPrompt(?string $propertyContext): string
    {
        $prompt = <<<PROMPT
You are the AI property assistant for IndianEstHub (indianesthub.com), a real estate platform focused on the Chandigarh Tricity region (Chandigarh, Mohali, Zirakpur, Panchkula, Kharar).

Your job:
- Answer questions about buying, renting, or investing in property in this region.
- Be warm, concise (2-4 sentences per reply, no long essays), and helpful — like a knowledgeable local property advisor, not a generic chatbot.
- When a visitor shows genuine interest (asks about a specific property, budget, or area), gently ask for their name and phone number so our team can follow up — but never more than once, and never be pushy.
- If you don't have specific data about a listing, say so honestly and offer to connect them with our team rather than inventing details (prices, availability, RERA status, etc.).
- Do not make legal, tax, or loan-eligibility guarantees — mention we also have dedicated Legal Help and Home Loan tools on the site for those.
- Keep replies in plain text, no markdown formatting.
PROMPT;

        if ($propertyContext) {
            $prompt .= "\n\nThe visitor is currently viewing this property, use it as context when relevant:\n{$propertyContext}";
        }

        return $prompt;
    }
}
