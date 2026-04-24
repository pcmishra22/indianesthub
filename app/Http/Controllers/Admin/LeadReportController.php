<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuilderLead;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\PropertyView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadReportController extends Controller
{
    public function index(Request $request)
    {
        // ── Date range filter (default: last 30 days) ─────────────────────────
        $from = $request->input('from') ? \Carbon\Carbon::parse($request->input('from'))->startOfDay()
                                        : now()->subDays(30)->startOfDay();
        $to   = $request->input('to')   ? \Carbon\Carbon::parse($request->input('to'))->endOfDay()
                                        : now()->endOfDay();

        // ── KPI Summary Cards ─────────────────────────────────────────────────
        $kpi = [
            'inquiries_total'     => Inquiry::whereBetween('created_at', [$from, $to])->count(),
            'inquiries_today'     => Inquiry::whereDate('created_at', today())->count(),
            'builder_leads_total' => BuilderLead::whereBetween('created_at', [$from, $to])->count(),
            'builder_leads_today' => BuilderLead::whereDate('created_at', today())->count(),
            'property_views'      => PropertyView::whereBetween('viewed_at', [$from, $to])->count(),
            'property_views_today'=> PropertyView::whereDate('viewed_at', today())->count(),
            'unique_visitors'     => PropertyView::whereBetween('viewed_at', [$from, $to])
                                        ->distinct('session_id')->count('session_id'),
        ];

        // ── Inquiries (paginated) ─────────────────────────────────────────────
        $inquiries = Inquiry::with('property')
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->paginate(15, ['*'], 'inq_page');

        // ── Builder Leads (paginated) ─────────────────────────────────────────
        $builderLeads = BuilderLead::with(['builder', 'project'])
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->paginate(15, ['*'], 'bl_page');

        // ── Most Viewed Properties ────────────────────────────────────────────
        $topProperties = Property::select('properties.*',
                DB::raw('COUNT(property_views.id) as view_count'))
            ->join('property_views', 'properties.id', '=', 'property_views.property_id')
            ->whereBetween('property_views.viewed_at', [$from, $to])
            ->groupBy('properties.id')
            ->orderByDesc('view_count')
            ->limit(10)
            ->get();

        // ── Recent Visitor Log ────────────────────────────────────────────────
        $recentVisits = PropertyView::with('property')
            ->whereBetween('viewed_at', [$from, $to])
            ->latest('viewed_at')
            ->limit(50)
            ->get();

        // ── Device Breakdown ─────────────────────────────────────────────────
        $deviceBreakdown = PropertyView::whereBetween('viewed_at', [$from, $to])
            ->selectRaw('device, COUNT(*) as total')
            ->groupBy('device')
            ->pluck('total', 'device');

        // ── Browser Breakdown ─────────────────────────────────────────────────
        $browserBreakdown = PropertyView::whereBetween('viewed_at', [$from, $to])
            ->selectRaw('browser, COUNT(*) as total')
            ->groupBy('browser')
            ->orderByDesc('total')
            ->pluck('total', 'browser');

        // ── Daily Views (last 14 days) for sparkline chart ───────────────────
        $dailyViews = PropertyView::selectRaw('DATE(viewed_at) as date, COUNT(*) as total')
            ->whereBetween('viewed_at', [now()->subDays(13)->startOfDay(), now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // ── Daily Inquiries (last 14 days) ────────────────────────────────────
        $dailyInquiries = Inquiry::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [now()->subDays(13)->startOfDay(), now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Build date labels for the last 14 days
        $chartLabels = [];
        $chartViews  = [];
        $chartInqs   = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d M');
            $chartViews[]  = $dailyViews[$date]  ?? 0;
            $chartInqs[]   = $dailyInquiries[$date] ?? 0;
        }

        // ── Lead Type Breakdown (builder leads) ───────────────────────────────
        $leadTypeBreakdown = BuilderLead::whereBetween('created_at', [$from, $to])
            ->selectRaw('lead_type, COUNT(*) as total')
            ->groupBy('lead_type')
            ->pluck('total', 'lead_type');

        // ── Top Cities by inquiries ───────────────────────────────────────────
        $topCities = DB::table('inquiries')
            ->join('properties', 'inquiries.property_id', '=', 'properties.id')
            ->whereBetween('inquiries.created_at', [$from, $to])
            ->selectRaw('properties.city, COUNT(inquiries.id) as total')
            ->groupBy('properties.city')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return view('backend.leads.report', compact(
            'kpi', 'from', 'to',
            'inquiries', 'builderLeads',
            'topProperties', 'recentVisits',
            'deviceBreakdown', 'browserBreakdown',
            'chartLabels', 'chartViews', 'chartInqs',
            'leadTypeBreakdown', 'topCities'
        ));
    }
}
