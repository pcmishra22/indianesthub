<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceLead;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceVendor;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Phase 1 marketplace flow: user clicks "Get quote" on a vendor card
 * inside a property page → this controller validates, persists, and
 * dispatches WhatsApp notifications to the vendor and the user.
 *
 * No cart, no payment, no vendor login. Just a qualified lead.
 */
class MarketplaceLeadController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'vendor_id'         => 'required|integer|exists:marketplace_vendors,id',
            'product_id'        => 'nullable|integer|exists:marketplace_products,id',
            'property_id'       => 'nullable|integer|exists:properties,id',
            'name'              => 'required|string|max:120',
            'email'             => 'nullable|email|max:160',
            'phone'             => 'required|string|max:20',
            'bhk_type'          => 'nullable|string|max:30',
            'window_count'      => 'nullable|integer|min:0|max:50',
            'fabric_preference' => 'nullable|string|max:120',
            'notes'             => 'nullable|string|max:1000',
        ]);

        $vendor  = MarketplaceVendor::findOrFail($validated['vendor_id']);
        $product = !empty($validated['product_id'])
            ? MarketplaceProduct::find($validated['product_id'])
            : null;

        $lead = MarketplaceLead::create([
            'property_id'        => $validated['property_id'] ?? null,
            'vendor_id'          => $vendor->id,
            'product_id'         => $product?->id,
            'name'               => $validated['name'],
            'email'              => $validated['email'] ?? null,
            'phone'              => $validated['phone'],
            'city'               => $product?->vendor?->city,
            'bhk_type'           => $validated['bhk_type'] ?? null,
            'window_count'       => $validated['window_count'] ?? null,
            'fabric_preference'  => $validated['fabric_preference'] ?? null,
            'notes'              => $validated['notes'] ?? null,
            'source_page'        => $request->header('Referer'),
            'ip_address'         => $request->ip(),
            'user_agent'         => substr((string) $request->userAgent(), 0, 500),
            'visitor_token'      => $request->cookie('visitor_token'),
            'status'             => 'new',
        ]);

        // Bump product leads counter (for admin sorting)
        if ($product) {
            $product->increment('leads_count');
        }

        // ── Notify the vendor via WhatsApp ────────────────────────────────
        $vendorMessage = $this->buildVendorMessage($lead, $vendor, $product);
        try {
            $wa = new WhatsAppNotificationService();
            $wa->send($vendor->whatsapp ?: $vendor->phone, $vendorMessage);
        } catch (\Throwable $e) {
            Log::warning('Marketplace vendor WhatsApp failed: ' . $e->getMessage());
        }

        // ── Notify the user via WhatsApp (best-effort) ───────────────────
        if (!empty($validated['phone'])) {
            $userMessage = $this->buildUserMessage($vendor, $product);
            try {
                $wa = new WhatsAppNotificationService();
                $wa->send($validated['phone'], $userMessage);
            } catch (\Throwable $e) {
                Log::info('Marketplace user WhatsApp skipped: ' . $e->getMessage());
            }
        }

        // ── Notify admin by email ────────────────────────────────────────
        $adminEmail = config('app.contact_email', 'admin@indianesthub.com');
        try {
            Mail::raw(
                "New marketplace lead\n\n" .
                "Vendor: {$vendor->business_name}\n" .
                "Product: " . ($product?->name ?? 'General inquiry') . "\n" .
                "Customer: {$validated['name']} ({$validated['phone']})\n" .
                "BHK: " . ($validated['bhk_type'] ?? 'n/a') . " | Windows: " . ($validated['window_count'] ?? 'n/a') . "\n" .
                "Fabric: " . ($validated['fabric_preference'] ?? 'n/a') . "\n" .
                "Property ID: " . ($validated['property_id'] ?? 'n/a') . "\n" .
                "Source: " . ($request->header('Referer') ?? 'n/a') . "\n",
                function ($msg) use ($adminEmail) {
                    $msg->to($adminEmail)->subject('New Marketplace Lead — ' . $adminEmail);
                }
            );
        } catch (\Throwable $e) {
            Log::warning('Marketplace admin email failed: ' . $e->getMessage());
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your request has been sent to ' . $vendor->business_name . '. They will contact you shortly.',
                'vendor'  => [
                    'name'    => $vendor->business_name,
                    'phone'   => $vendor->phone,
                    'whatsapp' => $vendor->whatsapp_link,
                ],
            ]);
        }

        return back()->with('success', 'Your request has been sent to ' . $vendor->business_name . '.');
    }

    private function buildVendorMessage(MarketplaceLead $lead, MarketplaceVendor $vendor, ?MarketplaceProduct $product): string
    {
        $lines = [
            "🔔 New lead from IndiaNestHub Marketplace",
            "",
            "Customer: {$lead->name}",
            "Phone: {$lead->phone}",
        ];
        if ($lead->email) {
            $lines[] = "Email: {$lead->email}";
        }
        $lines[] = "";
        if ($product) {
            $lines[] = "Interested in: {$product->name}";
            $lines[] = "Price range: " . $product->price_label;
        }
        if ($lead->bhk_type) {
            $lines[] = "BHK: {$lead->bhk_type}";
        }
        if ($lead->window_count) {
            $lines[] = "Windows: {$lead->window_count}";
        }
        if ($lead->fabric_preference) {
            $lines[] = "Fabric: {$lead->fabric_preference}";
        }
        if ($lead->notes) {
            $lines[] = "Notes: {$lead->notes}";
        }
        if ($lead->property_id) {
            $lines[] = "Property: https://indianesthub.com/properties/{$lead->property_id}";
        }
        $lines[] = "";
        $lines[] = "Please contact the customer within 2 hours. IndiaNestHub takes 8% commission on confirmed orders.";
        return implode("\n", $lines);
    }

    private function buildUserMessage(MarketplaceVendor $vendor, ?MarketplaceProduct $product): string
    {
        $msg = "✅ Your request has been sent to {$vendor->business_name} on IndiaNestHub.\n\n"
             . "They will contact you within 2 hours with a free measurement and quote.";
        if ($product) {
            $msg .= "\n\nReference: {$product->name}";
        }
        $msg .= "\n\nFor support: reply to this chat or call +91 7340753780.";
        return $msg;
    }
}
