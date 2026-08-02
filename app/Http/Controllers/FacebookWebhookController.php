<?php

namespace App\Http\Controllers;

use App\Services\FacebookLeadAdsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FacebookWebhookController extends Controller
{
    /**
     * GET — Meta's webhook verification handshake. Called once when you register
     * the webhook URL in the Meta App dashboard.
     */
    public function verify(Request $request)
    {
        $verifyToken = config('services.facebook.verify_token');

        if (
            $request->get('hub_mode') === 'subscribe' &&
            $verifyToken &&
            $request->get('hub_verify_token') === $verifyToken
        ) {
            return response($request->get('hub_challenge'), 200);
        }

        return response('Forbidden', 403);
    }

    /**
     * POST — actual lead notification events from Meta.
     */
    public function receive(Request $request, FacebookLeadAdsService $fb)
    {
        $rawBody = $request->getContent();
        $signature = $request->header('X-Hub-Signature-256');

        if (!$fb->verifySignature($rawBody, $signature)) {
            Log::warning('FacebookWebhookController: invalid signature on incoming webhook');
            return response('Invalid signature', 403);
        }

        $payload = $request->all();

        if (($payload['object'] ?? null) !== 'page') {
            return response('OK', 200);
        }

        foreach ($payload['entry'] ?? [] as $entry) {
            $pageId = $entry['id'] ?? null;

            foreach ($entry['changes'] ?? [] as $change) {
                if (($change['field'] ?? null) !== 'leadgen') {
                    continue;
                }

                $leadgenId = $change['value']['leadgen_id'] ?? null;
                $eventPageId = $change['value']['page_id'] ?? $pageId;

                if (!$leadgenId || !$eventPageId) {
                    continue;
                }

                try {
                    $result = $fb->processLead((string) $eventPageId, (string) $leadgenId);
                    Log::info('FacebookWebhookController: lead processed', [
                        'leadgen_id' => $leadgenId,
                        'page_id'    => $eventPageId,
                        'result'     => $result,
                    ]);
                } catch (\Throwable $e) {
                    // Never let one bad entry break the response — Meta will retry
                    // failed webhooks, and a 200 here prevents duplicate retries
                    // for entries we already handled successfully.
                    Log::error('FacebookWebhookController: error processing lead', [
                        'leadgen_id' => $leadgenId,
                        'message'    => $e->getMessage(),
                    ]);
                }
            }
        }

        // Always acknowledge with 200 — Meta disables webhooks that repeatedly fail.
        return response('OK', 200);
    }
}
