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
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $data['lead_type'] = 'general';
        $data['source'] = $data['source'] ?? 'website';
        $inquiry = Inquiry::create($data);
        $inquiry->recomputeHotScore();

        Log::info("Inquiry created for property ID: {$property->id}");

        $inquiryMessage = "New inquiry for property '{$property->title}' (ID: {$property->id}):\n" .
                          "Name: {$data['name']}\n" .
                          "Email: {$data['email']}\n" .
                          "Phone: {$data['phone']}\n" .
                          "Message: {$data['message']}";

        // NOTE: This is an IndianestHub lead. It must go to IndianestHub only —
        // never to the property's dealer/builder/agent own email or WhatsApp number.
        $fromAddress = config('mail.from.address', 'admin@indianesthub.com');
        $fromName = config('mail.from.name', 'India Nest Hub');

        Log::info("Starting notification process for Inquiry ID: {$inquiry->id}");
        Log::debug("Mailer Driver: " . config('mail.default'));

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

    // ── Index (CRM dashboard) ────────────────────────────────────────────────

    public function index(Request $request)
    {
        $dealerId = Auth::guard('dealer')->id();

        $query = Inquiry::where('broker_id', $dealerId)
            ->with('property:id,title')
            ->latest();

        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('lead_type'))   $query->where('lead_type', $request->lead_type);
        if ($request->filled('property_id')) $query->where('property_id', $request->property_id);
        if ($request->filled('heat')) {
            match ($request->heat) {
                'hot'  => $query->where('hot_score', '>=', 80),
                'warm' => $query->whereBetween('hot_score', [50, 79]),
                'cold' => $query->where('hot_score', '<', 50),
                default => null,
            };
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $inquiries = $query->paginate(20)->withQueryString();

        $base = Inquiry::where('broker_id', $dealerId);
        $stats = [
            'total'     => (clone $base)->count(),
            'new'       => (clone $base)->where('status', 'New')->count(),
            'contacted' => (clone $base)->where('status', 'Contacted')->count(),
            'converted' => (clone $base)->where('status', 'Converted')->count(),
            'hot'       => (clone $base)->where('hot_score', '>=', 80)->count(),
            'overdue'   => (clone $base)->whereNotNull('follow_up_at')
                                        ->where('follow_up_at', '<', now())
                                        ->whereNotIn('status', ['Converted', 'Lost'])
                                        ->count(),
            'today'     => (clone $base)->whereDate('created_at', today())->count(),
            'this_week' => (clone $base)->where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        $properties = Property::where('property_dealer_id', $dealerId)
            ->select('id', 'title')->orderBy('title')->get();

        return view('dealer.inquiries.index', compact('inquiries', 'stats', 'properties'));
    }

    // ── Update status ──────────────────────────────────────────────────────

    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        abort_if($inquiry->broker_id !== Auth::guard('dealer')->id(), 403);
        $request->validate(['status' => ['required', 'in:New,Contacted,Converted,Lost']]);
        $inquiry->update(['status' => $request->status]);
        $inquiry->recomputeHotScore();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Status updated.');
    }

    // ── Save notes + follow-up ─────────────────────────────────────────────

    public function saveNotes(Request $request, Inquiry $inquiry)
    {
        abort_if($inquiry->broker_id !== Auth::guard('dealer')->id(), 403);
        $request->validate([
            'notes'        => ['nullable', 'string', 'max:2000'],
            'follow_up_at' => ['nullable', 'date'],
        ]);
        $inquiry->update([
            'notes'        => $request->notes,
            'follow_up_at' => $request->follow_up_at ?: null,
        ]);
        return response()->json(['success' => true]);
    }

    // ── Add call log entry ─────────────────────────────────────────────────

    public function addCallLog(Request $request, Inquiry $inquiry)
    {
        abort_if($inquiry->broker_id !== Auth::guard('dealer')->id(), 403);
        $request->validate([
            'note'     => ['required', 'string', 'max:500'],
            'duration' => ['nullable', 'integer', 'min:0'],
        ]);
        $inquiry->addCallLog($request->note, $request->duration);

        if ($inquiry->status === 'New') {
            $inquiry->update(['status' => 'Contacted']);
        }

        return response()->json([
            'success'   => true,
            'call_log'  => $inquiry->fresh()->call_log,
            'hot_score' => $inquiry->fresh()->hot_score,
        ]);
    }

    // ── CSV export ─────────────────────────────────────────────────────────

    public function export(Request $request): StreamedResponse
    {
        $dealerId = Auth::guard('dealer')->id();
        $query = Inquiry::where('broker_id', $dealerId)
            ->with('property:id,title')
            ->latest();

        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('lead_type'))   $query->where('lead_type', $request->lead_type);
        if ($request->filled('property_id')) $query->where('property_id', $request->property_id);

        $inquiries = $query->get();

        $filename = 'leads-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($inquiries) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Phone', 'Email', 'Property', 'Type', 'Status', 'Hot Score', 'Follow-up', 'Message', 'Notes', 'Date']);
            foreach ($inquiries as $inquiry) {
                fputcsv($out, [
                    $inquiry->id,
                    $inquiry->name,
                    $inquiry->phone,
                    $inquiry->email ?? '',
                    $inquiry->property->title ?? 'General',
                    ucfirst($inquiry->lead_type),
                    $inquiry->status,
                    $inquiry->hot_score,
                    $inquiry->follow_up_at?->format('d M Y h:i A') ?? '',
                    $inquiry->message ?? '',
                    $inquiry->notes ?? '',
                    $inquiry->created_at->format('d M Y h:i A'),
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // ── Delete ─────────────────────────────────────────────────────────────

    public function destroy(Inquiry $inquiry)
    {
        abort_if($inquiry->broker_id !== Auth::guard('dealer')->id(), 403);
        $inquiry->delete();
        return back()->with('success', 'Lead removed.');
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
