<?php

namespace App\Services;

use App\Models\SocialMediaConnection;
use App\Models\Inquiry;
use App\Models\BuilderLead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookLeadAdsService
{
    protected string $appId;
    protected string $appSecret;
    protected string $graphVersion;
    protected string $graphBase;

    public function __construct()
    {
        $this->appId        = (string) config('services.facebook.app_id');
        $this->appSecret    = (string) config('services.facebook.app_secret');
        $this->graphVersion = config('services.facebook.graph_version', 'v21.0');
        $this->graphBase    = "https://graph.facebook.com/{$this->graphVersion}";
    }

    public function isConfigured(): bool
    {
        return $this->appId !== '' && $this->appSecret !== '';
    }

    // ── OAuth: connect flow ──────────────────────────────────────────────────

    public function getLoginUrl(string $redirectUri, string $state): string
    {
        $scopes = implode(',', [
            'pages_show_list',
            'pages_manage_metadata',
            'pages_read_engagement',
            'leads_retrieval',
            'pages_manage_ads',
            'instagram_basic',
        ]);

        $params = http_build_query([
            'client_id'     => $this->appId,
            'redirect_uri'  => $redirectUri,
            'state'         => $state,
            'scope'         => $scopes,
            'response_type' => 'code',
        ]);

        return "https://www.facebook.com/{$this->graphVersion}/dialog/oauth?{$params}";
    }

    /**
     * Exchanges the OAuth 'code' for a long-lived user access token.
     * @return array{token:?string, error:?string}
     */
    public function exchangeCodeForLongLivedToken(string $code, string $redirectUri): array
    {
        // Step 1: code -> short-lived user token
        $resp = Http::get("{$this->graphBase}/oauth/access_token", [
            'client_id'     => $this->appId,
            'redirect_uri'  => $redirectUri,
            'client_secret' => $this->appSecret,
            'code'          => $code,
        ]);

        if (!$resp->successful() || !$resp->json('access_token')) {
            Log::warning('FacebookLeadAdsService: code exchange failed', ['body' => $resp->body()]);
            return ['token' => null, 'error' => 'Could not connect to Facebook. Please try again.'];
        }

        $shortToken = $resp->json('access_token');

        // Step 2: short-lived -> long-lived user token (~60 days, refreshable by reconnecting)
        $resp2 = Http::get("{$this->graphBase}/oauth/access_token", [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $this->appId,
            'client_secret'     => $this->appSecret,
            'fb_exchange_token' => $shortToken,
        ]);

        if (!$resp2->successful() || !$resp2->json('access_token')) {
            Log::warning('FacebookLeadAdsService: long-lived token exchange failed', ['body' => $resp2->body()]);
            return ['token' => $shortToken, 'error' => null]; // fall back to short-lived rather than fail outright
        }

        return ['token' => $resp2->json('access_token'), 'error' => null];
    }

    /**
     * Lists the Facebook Pages the authorized user manages, with page access tokens.
     * @return array{pages:array, error:?string}
     */
    public function listManagedPages(string $userAccessToken): array
    {
        $resp = Http::get("{$this->graphBase}/me/accounts", [
            'access_token' => $userAccessToken,
            'fields'       => 'id,name,category,access_token,instagram_business_account{id,username}',
            'limit'        => 100,
        ]);

        if (!$resp->successful()) {
            Log::warning('FacebookLeadAdsService: listManagedPages failed', ['body' => $resp->body()]);
            return ['pages' => [], 'error' => 'Could not fetch your Facebook Pages. Please try again.'];
        }

        return ['pages' => $resp->json('data', []), 'error' => null];
    }

    /**
     * Subscribes a Page to leadgen webhook events for this app.
     */
    public function subscribePageToLeadgen(string $pageId, string $pageAccessToken): bool
    {
        $resp = Http::asForm()->post("{$this->graphBase}/{$pageId}/subscribed_apps", [
            'subscribed_fields' => 'leadgen',
            'access_token'      => $pageAccessToken,
        ]);

        if (!$resp->successful()) {
            Log::warning('FacebookLeadAdsService: subscribePageToLeadgen failed', ['page_id' => $pageId, 'body' => $resp->body()]);
            return false;
        }

        return (bool) $resp->json('success', false);
    }

    public function unsubscribePage(string $pageId, string $pageAccessToken): void
    {
        Http::delete("{$this->graphBase}/{$pageId}/subscribed_apps", ['access_token' => $pageAccessToken]);
    }

    // ── Webhook signature verification ───────────────────────────────────────

    public function verifySignature(string $rawBody, ?string $signatureHeader): bool
    {
        if (!$signatureHeader || !str_starts_with($signatureHeader, 'sha256=')) {
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $rawBody, $this->appSecret);

        return hash_equals($expected, $signatureHeader);
    }

    // ── Processing an incoming leadgen webhook event ─────────────────────────

    /**
     * @param string $pageId
     * @param string $leadgenId
     * @return array{created:bool, message:string}
     */
    public function processLead(string $pageId, string $leadgenId): array
    {
        $connection = SocialMediaConnection::active()
            ->where('platform', 'facebook')
            ->where('page_id', $pageId)
            ->first();

        if (!$connection) {
            Log::info('FacebookLeadAdsService: lead for unconnected page ignored', ['page_id' => $pageId]);
            return ['created' => false, 'message' => 'No active connection for this page.'];
        }

        // Idempotency: same leadgen_id should never create two leads (Meta may retry webhooks).
        $alreadyExists = $connection->connectable_type === \App\Models\Dealer::class
            ? Inquiry::where('external_lead_id', $leadgenId)->exists()
            : BuilderLead::where('external_lead_id', $leadgenId)->exists();

        if ($alreadyExists) {
            return ['created' => false, 'message' => 'Already processed.'];
        }

        $leadData = $this->fetchLeadFieldData($leadgenId, $connection->page_access_token);

        if (!$leadData) {
            $connection->update(['last_error' => "Failed to fetch lead {$leadgenId} from Graph API"]);
            return ['created' => false, 'message' => 'Could not fetch lead details from Facebook.'];
        }

        $fields = $this->parseFieldData($leadData['field_data'] ?? []);

        $connectable = $connection->connectable;
        if (!$connectable) {
            return ['created' => false, 'message' => 'Connection owner no longer exists.'];
        }

        if ($connection->connectable_type === \App\Models\Dealer::class) {
            $inquiry = Inquiry::create([
                'broker_id'        => $connectable->id,
                'property_id'      => null, // no specific property tied to a page-level lead form
                'name'             => $fields['name'] ?? 'Facebook Lead',
                'email'            => $fields['email'] ?? null,
                'phone'            => $fields['phone'] ?? null,
                'message'          => $fields['other'] ? implode(' | ', $fields['other']) : null,
                'lead_type'        => 'facebook_lead',
                'source'           => 'facebook',
                'external_lead_id' => $leadgenId,
            ]);
            $inquiry->recomputeHotScore();
        } else {
            $lead = BuilderLead::create([
                'builder_id'         => $connectable->id,
                'builder_project_id' => null, // no specific project tied to a page-level lead form
                'name'               => $fields['name'] ?? 'Facebook Lead',
                'email'              => $fields['email'] ?? null,
                'phone'              => $fields['phone'] ?? null,
                'message'            => $fields['other'] ? implode(' | ', $fields['other']) : null,
                'lead_type'          => 'facebook_lead',
                'source'             => 'facebook',
                'external_lead_id'   => $leadgenId,
                'status'             => 'new',
            ]);
            if (method_exists($lead, 'recomputeHotScore')) {
                $lead->recomputeHotScore();
            }
        }

        $connection->update(['last_lead_at' => now(), 'last_error' => null]);

        return ['created' => true, 'message' => 'Lead created.'];
    }

    protected function fetchLeadFieldData(string $leadgenId, string $pageAccessToken): ?array
    {
        $resp = Http::get("{$this->graphBase}/{$leadgenId}", [
            'access_token' => $pageAccessToken,
        ]);

        if (!$resp->successful()) {
            Log::warning('FacebookLeadAdsService: fetchLeadFieldData failed', ['leadgen_id' => $leadgenId, 'body' => $resp->body()]);
            return null;
        }

        return $resp->json();
    }

    /**
     * Meta lead forms return field_data as [{name, values:[...]}]. Field names vary
     * by form (custom questions), so we map common ones and bucket the rest as 'other'.
     */
    protected function parseFieldData(array $fieldData): array
    {
        $result = ['name' => null, 'email' => null, 'phone' => null, 'other' => []];

        $nameKeys  = ['full_name', 'name', 'first_name'];
        $emailKeys = ['email'];
        $phoneKeys = ['phone_number', 'phone'];

        foreach ($fieldData as $field) {
            $key = strtolower($field['name'] ?? '');
            $value = $field['values'][0] ?? null;
            if ($value === null) continue;

            if (in_array($key, $nameKeys) && !$result['name']) {
                $result['name'] = $value;
            } elseif (in_array($key, $emailKeys) && !$result['email']) {
                $result['email'] = $value;
            } elseif (in_array($key, $phoneKeys) && !$result['phone']) {
                $result['phone'] = $value;
            } else {
                $result['other'][] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $value;
            }
        }

        return $result;
    }
}
