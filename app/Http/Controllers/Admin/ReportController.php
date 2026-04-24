<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function expiringTomorrow()
    {
        $date = Carbon::tomorrow()->toDateString();
        $properties = Property::where('expiry_date', $date)->get();
        return view('backend.reports.expiring_properties', [
            'properties' => $properties,
            'reportTitle' => 'Properties Expiring Tomorrow',
            'reportDate' => $date
        ]);
    }

    public function expiringInAWeek()
    {
        $start = Carbon::now()->addDay()->toDateString();
        $end = Carbon::now()->addWeek()->toDateString();
        $properties = Property::whereBetween('expiry_date', [$start, $end])->get();
        return view('backend.reports.expiring_properties', [
            'properties' => $properties,
            'reportTitle' => 'Properties Expiring in a Week',
            'reportDate' => $start . ' to ' . $end
        ]);
    }
}
