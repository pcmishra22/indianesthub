<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\PropertyInquiryConfirmation;
use App\Mail\PropertyInquiryToDealer;
use App\Models\Dealer;
use App\Models\Inquiry;
use App\Models\LoanLead;
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
     * Show property detail page — /properties/{property:slug}
     */
    public function show(Property $property)
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

        // Increment views_count
        $property->increment('views_count');

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

        PropertyView::create([
            'property_id'     => $property->id,
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

        return view('frontend.property-details', compact(
            'property',
            'similarProperties',
            'viewsThisWeek',
            'inquiriesThisWeek',
            'builderProperties',
            'builderTotalProjects'
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

        // 1) Notify the dealer about the new inquiry
        $dealer = null;
        if ($property && $property->property_dealer_id) {
            $dealer = Dealer::find($property->property_dealer_id);
            Log::info("Property Dealer ID: " . $property->property_dealer_id . " - Dealer found: " . ($dealer ? $dealer->email : 'null'));
            if ($dealer && $dealer->email) {
                Log::info("Sending dealer email to: " . $dealer->email);
                Mail::to($dealer->email)->send(new PropertyInquiryToDealer($inquiry, $property));

                // WhatsApp notification to dealer
                if ($dealer->phone) {
                    Log::info("Sending WhatsApp to dealer: " . $dealer->phone);
                    $this->sendWhatsAppNotification(
                        $dealer->phone,
                        "New inquiry for your property '{$property->title}'. From: {$validated['name']}, Phone: {$validated['phone']}."
                    );
                }
            }
        } else {
            Log::warning("Property has no property_dealer_id set. Property ID: " . ($property ? $property->id : 'null'));
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
