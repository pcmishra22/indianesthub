<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\PropertyView;
use Illuminate\Http\Request;

class PropertyViewersController extends Controller
{
    public function index(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $from = $request->input('from');
        $to   = $request->input('to');

        $query = PropertyView::where('property_id', $property->id)
            ->where('event_type', 'page_view')
            ->orderByDesc('viewed_at');

        if ($from && $to) {
            $fromDt = \Carbon\Carbon::parse($from)->startOfDay();
            $toDt   = \Carbon\Carbon::parse($to)->endOfDay();
            $query->whereBetween('viewed_at', [$fromDt, $toDt]);
        }

        $propertyViews = $query
            ->paginate(200, [ // Paginate with 200 items per page, selecting specific columns
                'id', 'property_id', 'event_type', 'visitor_token', 'user_id', 'session_id',
                'ip_address', 'device', 'browser', 'referrer', 'page_url',
                'viewed_at'
            ]);

        // Interaction counts (call / WhatsApp clicks) for this property
        $callClicks     = PropertyView::where('property_id', $property->id)->where('event_type', 'call_click')->count();
        $whatsappClicks = PropertyView::where('property_id', $property->id)->where('event_type', 'whatsapp_click')->count();

        // The rest of your code remains the same
        $tokenList = $propertyViews
            ->pluck('visitor_token')
            ->filter()
            ->unique()
            ->values();

        $inquiriesByToken = Inquiry::where('property_id', $property->id)
            ->when($tokenList->isNotEmpty(), function ($q) use ($tokenList) {
                $q->whereIn('visitor_token', $tokenList);
            })
            ->get(['id', 'visitor_token', 'name', 'phone', 'email', 'created_at'])
            ->groupBy('visitor_token');

        return view('backend.properties.viewers', [
            'property' => $property,
            'propertyViews' => $propertyViews,
            'inquiriesByToken' => $inquiriesByToken,
            'callClicks' => $callClicks,
            'whatsappClicks' => $whatsappClicks,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
