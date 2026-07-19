<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceLead;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarketplaceLeadController extends Controller
{
    public function index(Request $request)
    {
        $q = MarketplaceLead::with(['vendor', 'product', 'property']);

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('vendor_id')) {
            $q->where('vendor_id', $request->vendor_id);
        }
        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $q->where(function ($x) use ($term) {
                $x->where('name', 'like', $term)
                  ->orWhere('phone', 'like', $term)
                  ->orWhere('email', 'like', $term);
            });
        }

        $leads = $q->latest()->paginate(25)->withQueryString();

        $stats = [
            'new'        => MarketplaceLead::where('status', 'new')->count(),
            'contacted'  => MarketplaceLead::where('status', 'contacted')->count(),
            'won'        => MarketplaceLead::where('status', 'won')->count(),
            'lost'       => MarketplaceLead::where('status', 'lost')->count(),
            'commission' => MarketplaceLead::where('commission_collected', true)->sum('commission_amount'),
        ];

        return view('backend.marketplace.leads.index', compact('leads', 'stats'));
    }

    public function show(MarketplaceLead $lead)
    {
        $lead->load('vendor', 'product', 'property');
        return view('backend.marketplace.leads.show', compact('lead'));
    }

    public function update(Request $request, MarketplaceLead $lead)
    {
        $data = $request->validate([
            'status'              => 'required|in:new,contacted,won,lost',
            'order_value'         => 'nullable|numeric|min:0',
            'commission_amount'   => 'nullable|numeric|min:0',
            'commission_collected'=> 'sometimes|boolean',
            'admin_notes'         => 'nullable|string|max:2000',
        ]);

        // Auto-stamp transitions
        if ($data['status'] === 'contacted' && !$lead->contacted_at) {
            $data['contacted_at'] = now();
        }
        if (in_array($data['status'], ['won', 'lost'], true) && !$lead->closed_at) {
            $data['closed_at'] = now();
        }
        // Auto-derive commission when marked won and no manual override
        if ($data['status'] === 'won'
            && empty($data['commission_amount'])
            && !empty($data['order_value'])
            && $lead->vendor) {
            $pct = (float) $lead->vendor->commission_pct;
            $data['commission_amount'] = round(((float) $data['order_value']) * $pct / 100, 2);
        }
        $data['commission_collected'] = (bool) ($data['commission_collected'] ?? false);

        $lead->update($data);

        return redirect()->route('admin.marketplace.leads.show', $lead)
            ->with('success', 'Lead updated.');
    }

    public function destroy(MarketplaceLead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.marketplace.leads.index')
            ->with('success', 'Lead removed.');
    }

    public function nudgeVendor(MarketplaceLead $lead)
    {
        $lead->load('vendor');
        if (!$lead->vendor) {
            return back()->with('error', 'Vendor not found for this lead.');
        }

        $msg = "Reminder from IndiaNestHub: please follow up with the customer for lead #{$lead->id} "
             . "({$lead->name}, {$lead->phone}). Thank you.";

        try {
            $wa = new WhatsAppNotificationService();
            $ok = $wa->send($lead->vendor->whatsapp ?: $lead->vendor->phone, $msg);
        } catch (\Throwable $e) {
            Log::warning('Marketplace nudge failed: ' . $e->getMessage());
            $ok = false;
        }

        if ($lead->status === 'new') {
            $lead->update(['status' => 'contacted', 'contacted_at' => now()]);
        }

        return back()->with($ok ? 'success' : 'error',
            $ok ? 'Vendor nudged via WhatsApp.' : 'Could not reach vendor.');
    }
}
