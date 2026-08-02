<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin, reusable wrapper around the Gemini generateContent REST endpoint.
 * Every AI feature (chat, description generator, legal checklist, etc.)
 * should go through this so error handling / rate-limit messaging stays
 * consistent in one place.
 */
class GeminiClient
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
     * @param array $contents  [['role' => 'user'|'model', 'parts' => [['text' => '...']]], ...]
     * @param string $systemPrompt
     * @param array $generationConfig  e.g. ['temperature' => 0.6, 'maxOutputTokens' => 400]
     * @return array{text:?string, error:bool, message:string, rateLimited:bool}
     */
    public function generate(array $contents, string $systemPrompt, array $generationConfig = []): array
    {
        if (!$this->isConfigured()) {
            return [
                'text' => null,
                'error' => true,
                'rateLimited' => false,
                'message' => 'AI is not configured — missing GEMINI_API_KEY.',
            ];
        }

        try {
            $response = Http::timeout(25)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
                [
                    'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                    'contents' => $contents,
                    'generationConfig' => array_merge([
                        'temperature'     => 0.6,
                        'maxOutputTokens' => 500,
                    ], $generationConfig),
                ]
            );

            if ($response->status() === 429) {
                Log::warning('GeminiClient: rate limit hit', ['body' => $response->body()]);

                return [
                    'text' => null,
                    'error' => true,
                    'rateLimited' => true,
                    'message' => "We're getting a lot of AI requests right now — please try again in about a minute.",
                ];
            }

            if (!$response->successful()) {
                Log::warning('GeminiClient: API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return [
                    'text' => null,
                    'error' => true,
                    'rateLimited' => false,
                    'message' => "AI request failed. Please try again in a moment.",
                ];
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (!$text) {
                return [
                    'text' => null,
                    'error' => true,
                    'rateLimited' => false,
                    'message' => "AI didn't return a usable response. Please try again.",
                ];
            }

            return ['text' => trim($text), 'error' => false, 'rateLimited' => false, 'message' => ''];

        } catch (\Throwable $e) {
            Log::error('GeminiClient: exception', ['message' => $e->getMessage()]);

            return [
                'text' => null,
                'error' => true,
                'rateLimited' => false,
                'message' => "Something went wrong reaching the AI service. Please try again.",
            ];
        }
    }

    /**
     * Convenience helper for one-shot (non-conversational) prompts.
     */
    public function generateOnce(string $systemPrompt, string $userPrompt, array $generationConfig = []): array
    {
        return $this->generate(
            [['role' => 'user', 'parts' => [['text' => $userPrompt]]]],
            $systemPrompt,
            $generationConfig
        );
    }
}
