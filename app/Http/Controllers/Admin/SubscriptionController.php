<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Dealer;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with('dealer')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('dealer', fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%"));
        }

        $subscriptions = $query->paginate(20)->withQueryString();

        $stats = [
            'total'      => Subscription::count(),
            'active'     => Subscription::where('status', 'active')->count(),
            'expired'    => Subscription::where('status', 'expired')->count(),
            'cancelled'  => Subscription::where('status', 'cancelled')->count(),
            'basic'      => Subscription::where('plan', 'basic')->where('status', 'active')->count(),
            'premium'    => Subscription::where('plan', 'premium')->where('status', 'active')->count(),
            'enterprise' => Subscription::where('plan', 'enterprise')->where('status', 'active')->count(),
            'revenue'    => Subscription::where('status', 'active')->sum('price'),
            'expiring_soon' => Subscription::where('status', 'active')
                ->whereDate('end_date', '<=', now()->addDays(7))->count(),
        ];

        // Dealers without any subscription (for "Add Subscription" dropdown)
        $unsubscribedDealers = Dealer::whereDoesntHave('subscription',
            fn($q) => $q->where('status', 'active'))->limit(100)->get();

        return view('backend.subscriptions.index',
            compact('subscriptions', 'stats', 'unsubscribedDealers'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load('dealer');
        return view('backend.subscriptions.show', compact('subscription'));
    }

    public function create()
    {
        $dealers = Dealer::orderBy('name')->get();
        return view('backend.subscriptions.create', compact('dealers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_dealer_id' => 'required|exists:property_dealers,id',
            'plan'               => 'required|in:basic,premium,enterprise',
            'price'              => 'required|numeric|min:0',
            'property_limit'     => 'required|integer|min:1',
            'featured_limit'     => 'required|integer|min:0',
            'priority_support'   => 'boolean',
            'analytics_access'   => 'boolean',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after:start_date',
            'status'             => 'required|in:active,expired,cancelled',
        ]);

        $data['renewal_date']     = $data['end_date'];
        $data['priority_support'] = $request->boolean('priority_support');
        $data['analytics_access'] = $request->boolean('analytics_access');

        Subscription::create($data);

        return redirect()->route('admin.subscriptions.index')
                         ->with('success', 'Subscription created successfully.');
    }

    public function edit(Subscription $subscription)
    {
        $dealers = Dealer::orderBy('name')->get();
        return view('backend.subscriptions.edit', compact('subscription', 'dealers'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'plan'             => 'required|in:basic,premium,enterprise',
            'price'            => 'required|numeric|min:0',
            'property_limit'   => 'required|integer|min:1',
            'featured_limit'   => 'required|integer|min:0',
            'priority_support' => 'boolean',
            'analytics_access' => 'boolean',
            'end_date'         => 'required|date',
            'status'           => 'required|in:active,expired,cancelled',
        ]);

        $data['renewal_date']     = $data['end_date'];
        $data['priority_support'] = $request->boolean('priority_support');
        $data['analytics_access'] = $request->boolean('analytics_access');

        $subscription->update($data);

        return redirect()->route('admin.subscriptions.index')
                         ->with('success', 'Subscription updated.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions.index')
                         ->with('success', 'Subscription deleted.');
    }
}
