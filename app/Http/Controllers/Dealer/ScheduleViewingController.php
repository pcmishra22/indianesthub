<?php
namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ScheduleViewing;

class ScheduleViewingController extends Controller
{
    public function index()
    {
        $dealerId = Auth::guard('dealer')->id();
        $viewings = ScheduleViewing::with('property')
            ->where('dealer_id', $dealerId)
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->paginate(20);
        return view('dealer.schedule_viewings.index', compact('viewings'));
    }
}
