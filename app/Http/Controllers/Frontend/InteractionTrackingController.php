<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BuilderView;
use App\Models\BuilderLead;
use App\Models\Property;
use App\Models\ProjectView;
use App\Models\PropertyView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionTrackingController extends Controller
{
    /**
     * Log a "what did they do" event (call click / whatsapp click) for a
     * property, builder, or project page. Called via a fire-and-forget
     * beacon from the frontend so it never blocks navigation.
     *
     * Expected payload:
     *   entity_type: property|builder|project
     *   entity_id:   int
     *   event_type:  call_click|whatsapp_click
     */
    public function track(Request $request)
    {
        $validated = $request->validate([
            'entity_type' => 'required|in:property,builder,project',
            'entity_id'   => 'required|integer',
            'event_type'  => 'required|in:call_click,whatsapp_click',
        ]);

        $visitorToken = $request->cookie('visitor_token');

        $common = [
            'event_type'    => $validated['event_type'],
            'user_id'       => Auth::id(),
            'session_id'    => $request->session()->getId(),
            'visitor_token' => $visitorToken,
            'ip_address'    => $request->ip(),
            'device'        => $this->detectDevice($request->userAgent() ?? ''),
            'browser'       => $this->detectBrowser($request->userAgent() ?? ''),
            'referrer'      => $request->headers->get('referer'),
            'page_url'      => $request->headers->get('referer'),
            'viewed_at'     => now(),
        ];

        match ($validated['entity_type']) {
            'property' => PropertyView::create(['property_id' => $validated['entity_id']] + $common),
            'builder'  => BuilderView::create(['builder_id' => $validated['entity_id']] + $common),
            'project'  => ProjectView::create(['builder_project_id' => $validated['entity_id']] + $common),
        };

        // Every call/WhatsApp tap on a builder-owned property becomes a real,
        // timestamped BuilderLead the instant it happens — not just an
        // anonymous row buried in property_views. Staff then have something
        // to work from in the "Live Call Clicks" queue when the phone rings,
        // and can fill in the caller's name/phone once they've actually
        // spoken, rather than having no record at all unless they remember
        // to create one manually.
        if ($validated['entity_type'] === 'property') {
            $property = Property::find($validated['entity_id']);

            if ($property && $property->builder_id) {
                $user = Auth::user();

                $recentDuplicate = $visitorToken && BuilderLead::where('property_id', $property->id)
                    ->where('lead_type', $validated['event_type'])
                    ->where('visitor_token', $visitorToken)
                    ->where('created_at', '>=', now()->subMinutes(30))
                    ->exists();

                if (!$recentDuplicate) {
                    $lead = BuilderLead::create([
                        'builder_id'         => $property->builder_id,
                        'builder_project_id' => $property->builder_project_id,
                        'property_id'        => $property->id,
                        'name'               => $user->name ?? null,
                        'email'              => $user->email ?? null,
                        'phone'              => $user->phone ?? null,
                        'message'            => "Auto-logged from a {$validated['event_type']} on \"{$property->title}\".",
                        'lead_type'          => $validated['event_type'], // call_click | whatsapp_click
                        'source'             => 'website',
                        'status'             => 'new',
                        'ip_address'         => $request->ip(),
                        'user_agent'         => $request->userAgent(),
                        'visitor_token'      => $visitorToken,
                    ]);

                    $lead->recomputeHotScore();
                }
            }
        }

        return response()->json(['success' => true]);
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
}
