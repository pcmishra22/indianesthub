<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
        ]);
        $property = \App\Models\Property::find($data['property_id']);
        $data['broker_id'] = $property ? $property->property_dealer_id : null;
        $inquiry = Inquiry::create($data);

        // Email notification to owner/agent
        $owner = $property ? $property->dealer : null;
        if ($owner && $owner->email) {
            \Mail::raw(
                "New inquiry for property '{$property->title}':\nName: {$data['name']}\nEmail: {$data['email']}\nPhone: {$data['phone']}\nMessage: {$data['message']}",
                function ($message) use ($owner, $property) {
                    $message->to($owner->email)
                            ->subject('New Property Inquiry');
                }
            );
        }

        return redirect()->back()->with('success', 'Inquiry submitted successfully!');
    }
    public function index(Request $request)
    {
        $dealerId = Auth::guard('dealer')->id();
        $inquiries = Inquiry::where('broker_id', $dealerId)
            ->with('property')
            ->latest()
            ->paginate(20);
        return view('dealer.inquiries.index', compact('inquiries'));
    }
}
