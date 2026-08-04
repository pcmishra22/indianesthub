<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\BuilderLead;
use App\Models\Contact;
use App\Models\Dealer;
use App\Models\Inquiry;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Review;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today      = today();
        $weekStart  = now()->startOfWeek();
        $monthStart = now()->startOfMonth();

        // ── Totals ───────────────────────────────────────────────────────────
        $totals = [
            'users'             => User::count(),
            'properties'        => Property::count(),
            'builders'          => Builder::count(),
            'dealers'           => Dealer::count(),
            'service_providers' => ServiceProvider::count(),
        ];

        // ── "Added today" counters ──────────────────────────────────────────
        $today_counts = [
            'properties'        => Property::whereDate('created_at', $today)->count(),
            'builders'          => Builder::whereDate('created_at', $today)->count(),
            'dealers'           => Dealer::whereDate('created_at', $today)->count(),
            'service_providers' => ServiceProvider::whereDate('created_at', $today)->count(),
            'users'             => User::whereDate('created_at', $today)->count(),
            'inquiries'         => Inquiry::whereDate('created_at', $today)->count(),
            'builder_leads'     => BuilderLead::whereDate('created_at', $today)->count(),
        ];

        // ── "Added this week / this month" counters ─────────────────────────
        $week_counts = [
            'properties'    => Property::where('created_at', '>=', $weekStart)->count(),
            'builders'      => Builder::where('created_at', '>=', $weekStart)->count(),
            'dealers'       => Dealer::where('created_at', '>=', $weekStart)->count(),
            'inquiries'     => Inquiry::where('created_at', '>=', $weekStart)->count(),
            'builder_leads' => BuilderLead::where('created_at', '>=', $weekStart)->count(),
        ];

        $month_counts = [
            'properties'    => Property::where('created_at', '>=', $monthStart)->count(),
            'builders'      => Builder::where('created_at', '>=', $monthStart)->count(),
            'dealers'       => Dealer::where('created_at', '>=', $monthStart)->count(),
            'inquiries'     => Inquiry::where('created_at', '>=', $monthStart)->count(),
            'builder_leads' => BuilderLead::where('created_at', '>=', $monthStart)->count(),
        ];

        // ── Needs-attention / pending-approval queue ────────────────────────
        $pending = [
            'service_providers' => ServiceProvider::where('status', 'pending')->count(),
            'reviews'           => Review::where('status', 'pending')->count(),
            'contacts_new'      => Contact::where('status', 'new')->count(),
            'payments_pending'  => Payment::where('status', 'pending')->count(),
        ];

        // ── Recent activity feed (real records, merged & sorted) ───────────
        $recentProperties = Property::latest()->limit(5)->get()->map(function ($p) {
            return [
                'type'       => 'property',
                'icon'       => 'home',
                'color'      => 'primary',
                'title'      => 'New property listed: ' . $p->title,
                'created_at' => $p->created_at,
                'url'        => route('admin.properties.show', $p->id),
            ];
        });

        $recentDealers = Dealer::latest()->limit(5)->get()->map(function ($d) {
            $name = $d->company_name ?: trim($d->first_name . ' ' . $d->last_name);
            return [
                'type'       => 'dealer',
                'icon'       => 'user-plus',
                'color'      => 'success',
                'title'      => 'New dealer registered: ' . ($name ?: 'Dealer #' . $d->id),
                'created_at' => $d->created_at,
                'url'        => route('admin.dealers.show', $d->id),
            ];
        });

        $recentBuilders = Builder::latest()->limit(5)->get()->map(function ($b) {
            return [
                'type'       => 'builder',
                'icon'       => 'briefcase',
                'color'      => 'warning',
                'title'      => 'New builder registered: ' . ($b->company_name ?: $b->name),
                'created_at' => $b->created_at,
                'url'        => route('admin.builders.show', $b->id),
            ];
        });

        $recentServiceProviders = ServiceProvider::latest()->limit(5)->get()->map(function ($sp) {
            return [
                'type'       => 'service_provider',
                'icon'       => 'tool',
                'color'      => 'info',
                'title'      => 'New service provider: ' . $sp->display_name,
                'created_at' => $sp->created_at,
                'url'        => route('admin.service-providers.show', $sp->id),
            ];
        });

        $recentInquiries = Inquiry::with('property')->latest()->limit(5)->get()->map(function ($i) {
            return [
                'type'       => 'inquiry',
                'icon'       => 'mail',
                'color'      => 'primary',
                'title'      => 'Property inquiry from ' . $i->name . ($i->property ? ' — ' . $i->property->title : ''),
                'created_at' => $i->created_at,
                'url'        => route('admin.inquiries.index', ['type' => 'property']),
            ];
        });

        $recentBuilderLeads = BuilderLead::with(['builder', 'project'])->latest()->limit(5)->get()->map(function ($l) {
            $subject = optional($l->project)->title ?? optional($l->builder)->company_name;
            return [
                'type'       => 'builder_lead',
                'icon'       => 'layers',
                'color'      => 'success',
                'title'      => 'Builder lead from ' . $l->name . ($subject ? ' — ' . $subject : ''),
                'created_at' => $l->created_at,
                'url'        => route('admin.builder-leads.show', $l->id),
            ];
        });

        $recentActivity = collect()
            ->concat($recentProperties)
            ->concat($recentDealers)
            ->concat($recentBuilders)
            ->concat($recentServiceProviders)
            ->concat($recentInquiries)
            ->concat($recentBuilderLeads)
            ->sortByDesc('created_at')
            ->values()
            ->take(12);

        return view('backend.dashboard', compact(
            'totals', 'today_counts', 'week_counts', 'month_counts',
            'pending', 'recentActivity'
        ));
    }
}
