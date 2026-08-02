<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\SocialMediaConnection;
use App\Services\FacebookLeadAdsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialConnectionController extends Controller
{
    public function index(FacebookLeadAdsService $fb)
    {
        $dealer = Auth::guard('dealer')->user();

        $connections = SocialMediaConnection::where('connectable_type', Dealer::class)
            ->where('connectable_id', $dealer->id)
            ->get();

        return view('dealer.marketing.social-connections', [
            'connections' => $connections,
            'fbConfigured' => $fb->isConfigured(),
        ]);
    }

    public function connect(Request $request, FacebookLeadAdsService $fb)
    {
        if (!$fb->isConfigured()) {
            return back()->with('error', 'Facebook integration is not configured on the server yet. Please contact support.');
        }

        $state = Str::random(40);
        session(['fb_oauth_state' => $state, 'fb_oauth_owner' => 'dealer']);

        return redirect($fb->getLoginUrl(route('dealer.social.callback'), $state));
    }

    public function callback(Request $request, FacebookLeadAdsService $fb)
    {
        if ($request->get('error')) {
            return redirect()->route('dealer.social.index')
                ->with('error', 'Facebook connection was cancelled or denied.');
        }

        if (!$request->get('code') || $request->get('state') !== session('fb_oauth_state')) {
            return redirect()->route('dealer.social.index')->with('error', 'Invalid or expired connection request. Please try again.');
        }

        $exchange = $fb->exchangeCodeForLongLivedToken($request->get('code'), route('dealer.social.callback'));

        if (!$exchange['token']) {
            return redirect()->route('dealer.social.index')->with('error', $exchange['error'] ?? 'Could not connect to Facebook.');
        }

        session(['fb_user_token' => $exchange['token']]);

        $pagesResult = $fb->listManagedPages($exchange['token']);

        if ($pagesResult['error']) {
            return redirect()->route('dealer.social.index')->with('error', $pagesResult['error']);
        }

        if (empty($pagesResult['pages'])) {
            return redirect()->route('dealer.social.index')
                ->with('error', 'No Facebook Pages found for your account. You need to be an admin of a Facebook Page to connect it.');
        }

        // Single page → connect immediately. Multiple pages → let them pick.
        if (count($pagesResult['pages']) === 1) {
            return $this->finalizeConnection($pagesResult['pages'][0], $fb);
        }

        session(['fb_available_pages' => $pagesResult['pages']]);
        return redirect()->route('dealer.social.select-page');
    }

    public function showPagePicker()
    {
        $pages = session('fb_available_pages');
        if (!$pages) {
            return redirect()->route('dealer.social.index')->with('error', 'Session expired. Please reconnect.');
        }
        return view('dealer.marketing.select-page', ['pages' => $pages]);
    }

    public function selectPage(Request $request, FacebookLeadAdsService $fb)
    {
        $request->validate(['page_id' => 'required|string']);

        $pages = session('fb_available_pages', []);
        $page = collect($pages)->firstWhere('id', $request->page_id);

        if (!$page) {
            return redirect()->route('dealer.social.index')->with('error', 'Selected page not found. Please reconnect.');
        }

        return $this->finalizeConnection($page, $fb);
    }

    protected function finalizeConnection(array $page, FacebookLeadAdsService $fb)
    {
        $dealer = Auth::guard('dealer')->user();

        $subscribed = $fb->subscribePageToLeadgen($page['id'], $page['access_token']);

        $connection = SocialMediaConnection::updateOrCreate(
            [
                'connectable_type' => Dealer::class,
                'connectable_id'   => $dealer->id,
                'page_id'          => $page['id'],
            ],
            [
                'platform'            => 'facebook',
                'page_name'           => $page['name'] ?? null,
                'page_category'       => $page['category'] ?? null,
                'ig_business_id'      => $page['instagram_business_account']['id'] ?? null,
                'ig_username'         => $page['instagram_business_account']['username'] ?? null,
                'page_access_token'   => $page['access_token'],
                'connected_by_name'   => $dealer->name ?? $dealer->email ?? null,
                'is_active'           => true,
                'leadgen_subscribed'  => $subscribed,
                'last_error'          => $subscribed ? null : 'Could not subscribe to lead notifications. Try disconnecting and reconnecting.',
            ]
        );

        session()->forget(['fb_oauth_state', 'fb_user_token', 'fb_available_pages']);

        $message = $subscribed
            ? "Connected \"{$connection->page_name}\" — leads from this Page's ads will now appear in your Leads dashboard automatically."
            : "Connected \"{$connection->page_name}\", but we couldn't enable lead notifications. Please try disconnecting and reconnecting.";

        return redirect()->route('dealer.social.index')->with($subscribed ? 'success' : 'error', $message);
    }

    public function disconnect(SocialMediaConnection $connection, FacebookLeadAdsService $fb)
    {
        $dealer = Auth::guard('dealer')->user();
        abort_if($connection->connectable_type !== Dealer::class || $connection->connectable_id !== $dealer->id, 403);

        try {
            $fb->unsubscribePage($connection->page_id, $connection->page_access_token);
        } catch (\Throwable $e) {
            // Non-fatal — proceed to remove the local connection regardless.
        }

        $connection->delete();

        return redirect()->route('dealer.social.index')->with('success', 'Facebook Page disconnected.');
    }
}
