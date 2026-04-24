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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PropertyDetailsController extends Controller
{
    /**
     * Show property detail page — /properties/{property:slug}
     */
    public function show(Property $property)
    {
        $property->load('images', 'dealer', 'builder');

        // Increment views_count
        $property->increment('views_count');

        // Detect device & browser from User-Agent
        $userAgent = request()->userAgent() ?? '';
        $device    = $this->detectDevice($userAgent);
        $browser   = $this->detectBrowser($userAgent);

        // Log detailed visit in property_views
        PropertyView::create([
            'property_id' => $property->id,
            'user_id'     => Auth::id(),
            'session_id'  => request()->session()->getId(),
            'ip_address'  => request()->ip(),
            'device'      => $device,
            'browser'     => $browser,
            'referrer'    => request()->headers->get('referer'),
            'page_url'    => request()->fullUrl(),
            'viewed_at'   => now(),
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

        // Similar properties (same city + type, exclude current)
        $similarProperties = Property::with('images')
            ->where('id', '!=', $property->id)
            ->where('city', $property->city)
            ->where('property_type', $property->property_type)
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired'])
            ->limit(4)
            ->get();

        return view('frontend.property-details', compact('property', 'similarProperties'));
    }

    /**
     * Handle property inquiry form submission.
     */
    public function submitInquiry(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'nullable|string|max:20',
            'message'     => 'required|string',
            'property_id' => 'required|integer|exists:properties,id',
        ]);

        $property = Property::find($validated['property_id']);
        $brokerId = $property ? $property->property_dealer_id : null;

        $inquiry = new Inquiry();
        $inquiry->name        = $validated['name'];
        $inquiry->email       = $validated['email'];
        $inquiry->phone       = $validated['phone'] ?? null;
        $inquiry->message     = $validated['message'];
        $inquiry->property_id = $validated['property_id'];
        $inquiry->broker_id   = $brokerId;
        $inquiry->ip_address  = $request->ip();
        $inquiry->user_agent  = $request->userAgent();
        $inquiry->source      = 'website';
        $inquiry->save();

        // ── Fire queued emails ────────────────────────────────────────────────
        // 1) Notify the dealer about the new inquiry
        if ($property && $property->property_dealer_id) {
            $dealer = Dealer::find($property->property_dealer_id);
            if ($dealer && $dealer->email) {
                Mail::to($dealer->email)->queue(new PropertyInquiryToDealer($inquiry, $property));
            }
        }

        // 2) Send confirmation to the buyer
        Mail::to($inquiry->email)->queue(new PropertyInquiryConfirmation($inquiry, $property));
        // ─────────────────────────────────────────────────────────────────────

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
}
