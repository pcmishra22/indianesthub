<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Property;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        // Restrict inquiry submission to logged-in users
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to send an inquiry.');
        }

        $data = $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
        ]);
        $property = Property::findOrFail($data['property_id']);
        $data['broker_id'] = $property ? $property->property_dealer_id : null;
        $inquiry = Inquiry::create($data);
        
        Log::info("Inquiry created for property ID: {$property->id}");

        $inquiryMessage = "New inquiry for property '{$property->title}' (ID: {$property->id}):\n" .
                          "Name: {$data['name']}\n" .
                          "Email: {$data['email']}\n" .
                          "Phone: {$data['phone']}\n" .
                          "Message: {$data['message']}";

        // 1. Notification to Dealer/Owner
        $owner = $property ? $property->dealer : null;
        $fromAddress = config('mail.from.address', 'support@indianesthub.com');
        $fromName = config('mail.from.name', 'India Nest Hub');

        Log::info("Starting notification process for Inquiry ID: {$inquiry->id}");
        Log::debug("Mailer Driver: " . config('mail.default'));

        try {
            if ($owner && $owner->email) {
                Log::info("Attempting to send email to dealer: {$owner->email}");
                Mail::raw(
                    $inquiryMessage,
                    function ($message) use ($owner, $fromAddress, $fromName) {
                        $message->from($fromAddress, $fromName)
                                ->to($owner->email)
                                ->subject('New Property Inquiry');
                    }
                );
                
                if ($owner->phone) { // Assuming 'phone' field stores a WhatsApp-capable number
                    $this->sendWhatsAppNotification($owner->phone, "New inquiry for your property '{$property->title}'. From: {$data['name']}, Phone: {$data['phone']}.");
                }
            } else {
                Log::warning("No owner or email found for property ID: {$property->id}");
            }
        } catch (\Exception $e) {
            Log::error("Error sending dealer inquiry notification: " . $e->getMessage());
        }

        // 2. Notification to Site Admins (Urgent Fix)
        // We include both the configured admin email and the specific ones you provided
        $adminRecipients = array_unique(array_filter([
            config('app.contact_email'),
            'admin@indianesthub.com',
            'pcmishra22@gmail.com'
        ]));

        try {
            if (!empty($adminRecipients)) {
                Log::info("Attempting to send email to admins: " . implode(', ', $adminRecipients));
                Mail::raw(
                    "Site Admin: New inquiry received.\n" . $inquiryMessage . "\nProperty Link: " . url('/properties/' . $property->id), // Adjust route as necessary for public property view
                    function ($message) use ($adminRecipients, $fromAddress, $fromName) {
                        $message->from($fromAddress, $fromName)
                                ->to($adminRecipients)
                                ->subject('New Property Inquiry - Admin Notification');
                    }
                );
            }

            // 3. WhatsApp Notification to Admin
            $adminWhatsAppNumber = config('app.whatsapp_number') ?: '7340753780';
            if ($adminWhatsAppNumber) {
                $this->sendWhatsAppNotification($adminWhatsAppNumber, "New property inquiry for '{$property->title}'. Contact: {$data['name']}, {$data['phone']}.");
            }
        } catch (\Exception $e) {
            Log::error("Error sending admin inquiry notification: " . $e->getMessage());
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
     * Send WhatsApp notification using the WhatsAppNotificationService.
     */
    private function sendWhatsAppNotification(string $recipientNumber, string $message): void
    {
        $whatsappService = new WhatsAppNotificationService();
        $whatsappService->send($recipientNumber, $message);
    }
}
