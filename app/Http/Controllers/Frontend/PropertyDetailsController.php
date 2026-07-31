<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\PropertyInquiryConfirmation;
use App\Mail\PropertyInquiryToDealer;
use App\Models\Dealer;
use App\Models\Inquiry;
use App\Models\LoanLead;
use App\Models\MarketplaceCategory;
use App\Models\MarketplaceProduct;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\RecentlyViewed;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

class PropertyDetailsController extends Controller
{
    /**
     * Show property detail page — /properties/{slug}
     */
    public function show(string $slug)
    {
        $property = Property::withTrashed()->where('slug', $slug)->first();

        if (!$property) {
            abort(404); // genuinely never existed — a real 404 is correct here
        }

        if ($property->trashed()) {
            return $this->showUnavailable($property);
        }

        return $this->render($property);
    }

    /**
     * Graceful page for a listing that used to exist but was removed
     * (soft-deleted) — returns HTTP 410 Gone, which tells search engines
     * this is an intentional, permanent removal (a stronger, more decisive
     * signal than a plain 404), while still giving a real visitor something
     * useful: similar active listings instead of a dead end.
     */
    protected function showUnavailable(Property $property)
    {
        $similar = Property::where('id', '!=', $property->id)
            ->when($property->city, fn ($q) => $q->where('city', $property->city))
            ->when($property->listing_type, fn ($q) => $q->where('listing_type', $property->listing_type))
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->latest()
            ->take(4)
            ->get();

        return response()
            ->view('frontend.property-unavailable', [
                'property' => $property,
                'similar'  => $similar,
            ], 410);
    }

    protected function render(Property $property)
    {
        $property->load('images', 'dealer', 'builder', 'builderProject');

        // Mask dealer contact details if the user is not logged in
        if (!Auth::check() && !$property->public_contact_enabled && $property->dealer) {
            $property->dealer->phone = 'Login to view';
            $property->dealer->email = 'Login to view';
        }

        // Mask builder contact details if the user is not logged in
        if (!Auth::check() && !$property->public_contact_enabled && $property->builder) {
            $property->builder->phone = 'Login to view';
            $property->builder->email = 'Login to view';
        }

        // Detect device & browser from User-Agent
        $userAgent = request()->userAgent() ?? '';
        $device    = $this->detectDevice($userAgent);
        $browser   = $this->detectBrowser($userAgent);

        // Stable visitor token (guest fingerprint)
        $visitorToken = request()->cookie('visitor_token');
        if (empty($visitorToken)) {
            $visitorToken = (string) \Illuminate\Support\Str::uuid();
            Cookie::queue('visitor_token', $visitorToken, 60 * 24 * 30); // ~180 days
        }

        // Only count this as a view if it looks like a real, distinct visit —
        // this keeps both `views_count` (shown to dealers/agents/builders) and
        // the admin's "Property Views" report meaningful instead of inflated by:
        //   1) Link-preview bots. Every time someone shares a property link on
        //      WhatsApp/Facebook/Twitter/etc, that platform's server fetches the
        //      page once to build the preview card — that's not a human looking
        //      at the listing, but without this check it counted as one.
        //   2) Repeat hits from the same visitor. Refreshing the page, or
        //      clicking back-and-forth into the same listing, previously
        //      inserted a brand new row and incremented the counter every time.
        if (!$this->isKnownBot($userAgent)) {
            $recentDuplicate = PropertyView::where('property_id', $property->id)
                ->where('event_type', 'page_view')
                ->where('viewed_at', '>=', now()->subMinutes(30))
                ->when(
                    Auth::check(),
                    fn ($q) => $q->where('user_id', Auth::id()),
                    fn ($q) => $q->where('visitor_token', $visitorToken)
                )
                ->exists();

            if (!$recentDuplicate) {
                $property->increment('views_count');

                $propertyView = PropertyView::create([
                    'property_id'     => $property->id,
                    'event_type'      => 'page_view',
                    'user_id'         => Auth::id(),
                    'session_id'      => request()->session()->getId(),
                    'visitor_token'   => $visitorToken,
                    'ip_address'      => request()->ip(),
                    'device'          => $device,
                    'browser'         => $browser,
                    'referrer'        => request()->headers->get('referer'),
                    'page_url'        => request()->fullUrl(),
                    'viewed_at'       => now(),
                ]);

                // Best-effort, non-blocking: resolve the visitor's country
                // and fill it in after this page has already been sent to
                // them. See IpGeolocationService for why this is designed to
                // never fail loudly or slow down the page.
                $ip = request()->ip();
                dispatch(function () use ($propertyView, $ip) {
                    $geo = app(\App\Services\IpGeolocationService::class)->lookup($ip);
                    if ($geo) {
                        $propertyView->update([
                            'country'      => $geo['country'],
                            'country_code' => $geo['country_code'],
                        ]);
                    }
                })->afterResponse();
            }
        }

        // Track in recently_viewed table
        $recentlyViewedData = [
            'property_id' => $property->id,
            'viewed_at'   => now(),
        ];

        if (Auth::check()) {
            $recentlyViewedData['user_id'] = Auth::id();
            RecentlyViewed::updateOrCreate(
                ['user_id' => Auth::id(), 'property_id' => $property->id],
                $recentlyViewedData
            );
        } else {
            $recentlyViewedData['session_id'] = request()->session()->getId();
            RecentlyViewed::updateOrCreate(
                ['session_id' => request()->session()->getId(), 'property_id' => $property->id],
                $recentlyViewedData
            );
        }

        // ── Social proof numbers ────────────────────────────────────────────
        // Views in last 7 days
        $viewsThisWeek = PropertyView::where('property_id', $property->id)
            ->where('viewed_at', '>=', now()->subDays(7))
            ->count();

        // Inquiries in last 7 days
        $inquiriesThisWeek = Inquiry::where('property_id', $property->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // ── Similar properties (same city + type + BHK, exclude current) ──
        $similarProperties = Property::with('images')
            ->where('id', '!=', $property->id)
            ->where('city', $property->city)
            ->where('property_type', $property->property_type)
            ->when($property->bhk_type, fn($q) => $q->where('bhk_type', $property->bhk_type))
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired'])
            ->limit(6)
            ->get();

        // Fallback: relax BHK filter if too few results
        if ($similarProperties->count() < 3) {
            $similarProperties = Property::with('images')
                ->where('id', '!=', $property->id)
                ->where('city', $property->city)
                ->where('property_type', $property->property_type)
                ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired'])
                ->limit(6)
                ->get();
        }

        // ── Builder context ────────────────────────────────────────────────
        $builderProperties = collect();
        $builderTotalProjects = 0;

        if ($property->builder) {
            $builderProperties = Property::with('images')
                ->where('builder_id', $property->builder_id)
                ->where('id', '!=', $property->id)
                ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired'])
                ->limit(4)
                ->get();

            $builderTotalProjects = $property->builder->projects()->count();
        }

        // ── Home Marketplace widget ───────────────────────────────────────
        // Load 3 active products in the property's city, preferring
        // products tagged for this BHK. Falls back to any active products
        // if nothing matches. Other categories are shown as "coming soon".
        $marketplaceProducts = collect();
        $marketplaceCategories = collect();

        try {
            $marketplaceCategories = MarketplaceCategory::active()
                ->orderBy('sort_order')
                ->get()
                ->map(function ($c) {
                    $c->product_count = MarketplaceProduct::where('category_id', $c->id)
                        ->where('is_active', true)
                        ->count();
                    return $c;
                });

            $base = MarketplaceProduct::with('vendor', 'category')
                ->where('is_active', true)
                ->whereHas('vendor', function ($v) {
                    $v->where('is_active', true);
                });

            $marketplaceProducts = $base
                ->orderByDesc('is_featured')
                ->orderByDesc('leads_count')
                ->orderBy('sort_order')
                ->limit(24)
                ->get()
                ->filter(fn ($p) => $p->fitsBhk($property->bhk_type))
                ->take(3)
                ->values();
        } catch (\Throwable $e) {
            // Marketplace tables not migrated yet — fail silently.
            Log::info('Marketplace widget skipped: ' . $e->getMessage());
        }

        return view('frontend.property-details', compact(
            'property',
            'similarProperties',
            'viewsThisWeek',
            'inquiriesThisWeek',
            'builderProperties',
            'builderTotalProjects',
            'marketplaceProducts',
            'marketplaceCategories'
        ));
    }

    /**
     * Handle property inquiry form submission.
     */
    public function submitInquiry(Request $request)
    {
        $property = Property::find($request->property_id);

        // Restrict inquiry submission to logged-in users unless public contact is enabled for this property
        if (!Auth::check() && (!$property || !$property->public_contact_enabled)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please login to send an inquiry.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to send an inquiry.');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'nullable|string|max:20',
            'message'     => 'required|string',
            'property_id' => 'required|integer|exists:properties,id',
        ]);

        $brokerId = $property ? $property->property_dealer_id : null;

        $inquiry = new Inquiry();
        $inquiry->name        = $validated['name'];
        $inquiry->email       = $validated['email'];
        $inquiry->phone       = $validated['phone'] ?? null;
        $inquiry->message     = $validated['message'];
        $inquiry->property_id = $validated['property_id'];
        $inquiry->broker_id   = $brokerId;
        $inquiry->ip_address     = $request->ip();
        $inquiry->user_agent     = $request->userAgent();
        $inquiry->visitor_token  = $request->cookie('visitor_token');
        $inquiry->source         = 'website';
        $inquiry->save();

        // ── Fire queued emails ────────────────────────────────────────────────
        $fromAddress = config('mail.from.address', 'admin@indianesthub.com');
        $fromName = config('mail.from.name', 'India Nest Hub');

        // NOTE: This is an IndianestHub lead. It must go to IndianestHub only —
        // never to the property's dealer/builder/agent own email or WhatsApp number.
        // The templated "inquiry received" email is sent to IndianestHub's own inbox
        // so the internal team has a nicely formatted copy alongside the plain-text
        // admin alert below.
        if ($property) {
            Mail::to(config('app.contact_email', 'admin@indianesthub.com'))
                ->send(new PropertyInquiryToDealer($inquiry, $property));
        }

        // 2) Send confirmation to the buyer
        Mail::to($inquiry->email)->send(new PropertyInquiryConfirmation($inquiry, $property));

        // 3) Send notification to admins
        $adminRecipients = array_unique(array_filter([
            config('app.contact_email'),
            'admin@indianesthub.com',
            'pcmishra22@gmail.com'
        ]));

        if (!empty($adminRecipients)) {
            $adminMessage = "Site Admin: New property inquiry received.\n\n" .
                "Property: {$property->title} (ID: {$property->id})\n" .
                "Name: {$validated['name']}\n" .
                "Email: {$validated['email']}\n" .
                "Phone: {$validated['phone']}\n" .
                "Message: {$validated['message']}\n" .
                "Property Link: " . url('/properties/' . $property->id);

            Mail::raw($adminMessage, function ($message) use ($adminRecipients, $fromAddress, $fromName) {
                $message->from($fromAddress, $fromName)
                    ->to($adminRecipients)
                    ->subject('New Property Inquiry - Admin Notification');
            });

            // WhatsApp notification to admin
            $adminWhatsAppNumber = config('app.whatsapp_number', '7340753780');
            Log::info("Admin WhatsApp number: " . $adminWhatsAppNumber);
            if ($adminWhatsAppNumber) {
                $this->sendWhatsAppNotification(
                    $adminWhatsAppNumber,
                    "New property inquiry for '{$property->title}'. Contact: {$validated['name']}, {$validated['phone']}."
                );
            }
        }
        // ──────────────────────────────────────────────────────────────────────

        // Auto-create a LoanLead if user checked "needs loan assistance"
        if ($request->boolean('needs_loan')) {
            LoanLead::create([
                'property_id'  => $validated['property_id'],
                'name'         => $validated['name'],
                'phone'        => $validated['phone'] ?? '',
                'email'        => $validated['email'],
                'source'       => 'inquiry-form',
                'source_page'  => $request->header('Referer'),
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->userAgent(),
            ]);
        }

        // Increment property inquiries_count
        if ($property) {
            $property->increment('inquiries_count');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Your inquiry has been submitted successfully!']);
        }

        return redirect()->back()->with('success', 'Your inquiry has been submitted successfully!');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Recognizes common search-engine crawlers and social-media link-preview
     * bots so their automated page fetches don't get counted as real visits.
     * Link-preview bots (WhatsApp, Facebook, Twitter/X, etc.) are the most
     * important case here — every time someone shares a property link, the
     * receiving platform's server fetches the page once to build the preview
     * card, which is not a person looking at the listing.
     *
     * This is a pragmatic allowlist-of-known-signatures approach, not a
     * security control — it won't catch every automated client, and that's
     * fine: the goal is just to make the "Property Views" number reflect
     * real interest, not to gate access to the page itself.
     */
    private function isKnownBot(string $ua): bool
    {
        if ($ua === '') {
            return true; // A real browser always sends a User-Agent; an empty one is almost certainly a script.
        }

        $ua = strtolower($ua);

        $signatures = [
            // Link-preview / "unfurling" bots — the main source of inflated counts
            // on a site whose links get shared over chat apps.
            'whatsapp', 'facebookexternalhit', 'facebot', 'twitterbot',
            'linkedinbot', 'telegrambot', 'discordbot', 'slackbot',
            'skypeuripreview', 'pinterest',
            // Search engine crawlers.
            'googlebot', 'bingbot', 'yandexbot', 'duckduckbot', 'baiduspider',
            'applebot', 'petalbot',
            // Generic automation / scraping tool signatures.
            'bot', 'crawler', 'spider', 'curl', 'wget', 'python-requests',
            'headlesschrome', 'phantomjs', 'scrapy',
        ];

        foreach ($signatures as $signature) {
            if (str_contains($ua, $signature)) {
                return true;
            }
        }

        return false;
    }

    private function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function detectBrowser(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'edg/'))    return 'Edge';
        if (str_contains($ua, 'opr/') || str_contains($ua, 'opera')) return 'Opera';
        if (str_contains($ua, 'chrome'))   return 'Chrome';
        if (str_contains($ua, 'firefox'))  return 'Firefox';
        if (str_contains($ua, 'safari'))   return 'Safari';
        if (str_contains($ua, 'msie') || str_contains($ua, 'trident')) return 'IE';
        return 'Other';
    }

    /**
     * Send WhatsApp notification using the WhatsAppNotificationService.
     */
    private function sendWhatsAppNotification(string $recipientNumber, string $message): void
    {
        $whatsappService = new WhatsAppNotificationService();
        $whatsappService->send($recipientNumber, $message);
    }
}
