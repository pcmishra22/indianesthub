<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ScheduleViewingConfirmation;
use App\Mail\ScheduleViewingToDealer;
use Illuminate\Http\Request;
use App\Models\LoanLead;
use App\Models\ScheduleViewing;
use App\Models\Property;
use App\Models\Dealer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ScheduleViewingController extends Controller
{
    public function submit(Request $request)
    {
        // Restrict scheduling viewings to logged-in users
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to schedule a viewing.'], 401);
        }

        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'dealer_id' => 'required|exists:property_dealers,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:32',
            'date' => 'required|date',
            'time' => 'required',
            'message' => 'nullable|string',
        ]);
        $schedule = ScheduleViewing::create($data);

        // Auto-create a LoanLead if user requested loan assistance
        if ($request->boolean('needs_loan')) {
            LoanLead::create([
                'property_id' => $data['property_id'],
                'name'        => $data['name'],
                'phone'       => $data['phone'] ?? '',
                'email'       => $data['email'],
                'source'      => 'schedule-form',
                'source_page' => $request->header('Referer'),
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        }

        // ── Fire queued emails ────────────────────────────────────────────────
        $property = Property::find($data['property_id']);
        $dealer   = Dealer::find($data['dealer_id']);

        // 1) Notify dealer about the scheduled viewing
        if ($dealer && $dealer->email && $property) {
            Mail::to($dealer->email)->queue(new ScheduleViewingToDealer($schedule, $property));
        }

        // 2) Send confirmation to the buyer/visitor
        if ($property) {
            Mail::to($data['email'])->queue(new ScheduleViewingConfirmation($schedule, $property));
        }
        // ─────────────────────────────────────────────────────────────────────

        return response()->json(['success' => true]);
    }
}
