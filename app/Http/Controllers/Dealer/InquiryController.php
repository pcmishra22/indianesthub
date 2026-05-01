<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Added for simulated WhatsApp notification

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
        $property = Property::find($data['property_id']);
        $data['broker_id'] = $property ? $property->property_dealer_id : null;
        $inquiry = Inquiry::create($data);

        $inquiryMessage = "New inquiry for property '{$property->title}' (ID: {$property->id}):\n" .
                          "Name: {$data['name']}\n" .
                          "Email: {$data['email']}\n" .
                          "Phone: {$data['phone']}\n" .
                          "Message: {$data['message']}";

        // Email notification to owner/agent
        $owner = $property ? $property->dealer : null;
        if ($owner && $owner->email) {
            Mail::raw(
                $inquiryMessage,
                function ($message) use ($owner, $property) {
                    $message->to($owner->email)
                            ->subject('New Property Inquiry');
                }
            );

            // WhatsApp notification to dealer
            if ($owner->phone) { // Assuming 'phone' field stores a WhatsApp-capable number
                $this->sendWhatsAppNotification($owner->phone, "New inquiry for your property '{$property->title}'. From: {$data['name']}, Phone: {$data['phone']}.");
            }
        }

        // Email notification to site admin
        $adminEmail = config('app.contact_email');
        if ($adminEmail) {
            Mail::raw(
                "Site Admin: New inquiry received.\n" . $inquiryMessage . "\nProperty Link: " . url('/properties/' . $property->id), // Adjust route as necessary for public property view
                function ($message) use ($adminEmail) {
                    $message->to($adminEmail)
                            ->subject('New Property Inquiry - Admin Notification');
                }
            );
        }

        // WhatsApp notification to site admin
        $adminWhatsAppNumber = config('app.whatsapp_number');
        if ($adminWhatsAppNumber) {
            $this->sendWhatsAppNotification($adminWhatsAppNumber, "New property inquiry for '{$property->title}'. Contact: {$data['name']}, {$data['phone']}.");
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

    /**
     * Placeholder for sending WhatsApp notifications.
     * In a real application, this would integrate with a third-party WhatsApp API (e.g., Twilio, MessageBird).
     * This method would handle the API calls, error handling, and message formatting.
     * For this example, it only logs the message.
     */
    private function sendWhatsAppNotification(string $recipientNumber, string $message): void
    {
        Log::info("WhatsApp Notification (simulated): To {$recipientNumber} - {$message}");
    }
}
