<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\BuilderLead;
use App\Models\BuilderView;
use Illuminate\Http\Request;

class BuilderViewersController extends Controller
{
    public function index(Request $request, $id)
    {
        $builder = Builder::findOrFail($id);

        $from = $request->input('from');
        $to   = $request->input('to');

        $query = BuilderView::where('builder_id', $builder->id)
            ->where('event_type', 'page_view')
            ->orderByDesc('viewed_at');

        if ($from && $to) {
            $fromDt = \Carbon\Carbon::parse($from)->startOfDay();
            $toDt   = \Carbon\Carbon::parse($to)->endOfDay();
            $query->whereBetween('viewed_at', [$fromDt, $toDt]);
        }

        $builderViews = $query->paginate(200);

        $tokenList = $builderViews->pluck('visitor_token')->filter()->unique()->values();

        $leadsByToken = BuilderLead::where('builder_id', $builder->id)
            ->when($tokenList->isNotEmpty(), function ($q) use ($tokenList) {
                $q->whereIn('visitor_token', $tokenList);
            })
            ->get(['id', 'visitor_token', 'name', 'phone', 'email', 'lead_type', 'created_at'])
            ->groupBy('visitor_token');

        return view('backend.builders.viewers', [
            'builder' => $builder,
            'builderViews' => $builderViews,
            'leadsByToken' => $leadsByToken,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
