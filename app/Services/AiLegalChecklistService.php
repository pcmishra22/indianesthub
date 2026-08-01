<?php

namespace App\Services;

class AiLegalChecklistService
{
    protected GeminiClient $client;

    public function __construct(GeminiClient $client)
    {
        $this->client = $client;
    }

    public function isConfigured(): bool
    {
        return $this->client->isConfigured();
    }

    /**
     * @param array $attrs  ['issue_type' => string, 'property_type' => ?string, 'city' => ?string, 'buyer_type' => ?string]
     * @return array{documents:array, steps:array, red_flags:array, error:bool, message:string}
     */
    public function generate(array $attrs): array
    {
        if (!$this->client->isConfigured()) {
            return $this->errorResult('AI legal checklist is not configured yet.');
        }

        $issueLabel = $this->issueLabel($attrs['issue_type'] ?? 'other');
        $propertyType = $attrs['property_type'] ?? 'not specified';
        $city = $attrs['city'] ?? 'Chandigarh Tricity (Chandigarh, Mohali, Zirakpur, Panchkula)';
        $buyerType = $attrs['buyer_type'] ?? 'resident Indian';

        $systemPrompt = <<<PROMPT
You are a property-law reference assistant for IndianEstHub, focused on real estate transactions in Punjab, Haryana, and Chandigarh (India), including registration, stamp duty, and sub-registrar processes typical in this region.

Task: produce a general-guidance checklist for the transaction described below. This is educational information, NOT legal advice for a specific case.

Respond with ONLY a valid JSON object, no markdown, no code fences, no commentary, in exactly this shape:
{
  "documents": ["...", "..."],
  "steps": ["...", "..."],
  "red_flags": ["...", "..."]
}

Rules:
- "documents": 5-9 documents typically required for this transaction type in this region (e.g. sale deed, encumbrance certificate, khasra/jamabandi where relevant, NOC, etc. — only include what's genuinely relevant to the transaction type given).
- "steps": 4-7 ordered, practical steps in the typical sequence for this transaction.
- "red_flags": 3-5 common warning signs or mistakes buyers/sellers should watch for in this type of transaction.
- Keep each item to one clear sentence, plain language, no legal jargon without explanation.
- Do not mention specific lawyers, companies, or fees/costs (they vary and we don't want to misstate them).
- Do not invent region-specific rules you're not confident about — prefer general, broadly-accurate guidance over specific-sounding but possibly wrong details.

Transaction details:
- Type of legal matter: {$issueLabel}
- Property type: {$propertyType}
- City/region: {$city}
- Buyer/applicant type: {$buyerType}
PROMPT;

        $result = $this->client->generateOnce(
            $systemPrompt,
            'Generate the checklist JSON now.',
            ['temperature' => 0.4, 'maxOutputTokens' => 700]
        );

        if ($result['error']) {
            return $this->errorResult($result['message']);
        }

        $parsed = $this->parseJson($result['text']);

        if (!$parsed) {
            return $this->errorResult("Couldn't generate a clean checklist this time. Please try again.");
        }

        return [
            'documents' => $parsed['documents'] ?? [],
            'steps' => $parsed['steps'] ?? [],
            'red_flags' => $parsed['red_flags'] ?? [],
            'error' => false,
            'message' => '',
        ];
    }

    protected function issueLabel(string $type): string
    {
        return \App\Models\LegalLead::issueTypeOptions()[$type] ?? 'General property legal matter';
    }

    protected function parseJson(string $raw): ?array
    {
        $raw = trim($raw);
        // Strip stray markdown code fences if the model added them despite instructions.
        $raw = preg_replace('/^```(?:json)?|```$/m', '', $raw);
        $raw = trim($raw);

        $data = json_decode($raw, true);

        return (json_last_error() === JSON_ERROR_NONE && is_array($data)) ? $data : null;
    }

    protected function errorResult(string $message): array
    {
        return ['documents' => [], 'steps' => [], 'red_flags' => [], 'error' => true, 'message' => $message];
    }
}
