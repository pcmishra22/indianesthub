<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Property;
use App\Services\EdmSenderService;
use App\Services\PropertyBrochureService;
use App\Services\PropertyEdmContentService;
use App\Services\SocialPostContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingController extends Controller
{
    public function __construct(
        protected PropertyBrochureService $brochures,
        protected SocialPostContentService $socialContent,
        protected PropertyEdmContentService $edmContent,
        protected EdmSenderService $edmSender,
    ) {
    }

    /**
     * Marketing Studio hub for a single property.
     * Unlike the Dealer/Builder versions, admin isn't restricted to
     * properties it owns — it can market any live listing.
     */
    public function index(Property $property)
    {
        $publicUrl = $property->slug ? route('property-details', $property) : null;

        return view('backend.properties.marketing', compact('property', 'publicUrl'));
    }

    /**
     * Generate the brochure PDF and stream it for download.
     * Also saves a copy against the property's brochure_pdf field.
     */
    public function brochure(Property $property)
    {
        $this->brochures->generateAndStore($property);

        return $this->brochures->render($property)
            ->download($this->brochures->downloadFilename($property));
    }

    /**
     * Social media post generator (canvas-based image + caption).
     */
    public function socialPost(Property $property)
    {
        $publicUrl = $property->slug ? route('property-details', $property) : null;
        $caption = $this->socialContent->caption($property, $publicUrl);

        return view('backend.properties.social-post', compact('property', 'publicUrl', 'caption'));
    }

    /**
     * EDM composer: shows leads who enquired about this property + subject/message form.
     */
    public function edm(Property $property)
    {
        $leads = Inquiry::where('property_id', $property->id)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'email'])
            ->unique('email')
            ->values();

        $subject = $this->edmContent->defaultSubject($property);
        $message = $this->edmContent->defaultMessage($property);
        $sendUrl = route('admin.properties.marketing.edm.send', $property->id);

        return view('backend.properties.edm', compact('property', 'leads', 'subject', 'message', 'sendUrl'));
    }

    /**
     * Send the EDM campaign to selected leads + manually entered emails.
     */
    public function sendEdm(Request $request, Property $property)
    {
        $validated = $request->validate([
            'subject'        => ['required', 'string', 'max:150'],
            'message'        => ['required', 'string', 'max:3000'],
            'lead_emails'    => ['nullable', 'array'],
            'lead_emails.*'  => ['email'],
            'extra_emails'   => ['nullable', 'string'],
        ]);

        $extraEmails = collect(preg_split('/[,\n\r]+/', $validated['extra_emails'] ?? ''))
            ->map(fn ($e) => trim($e))
            ->filter()
            ->filter(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL));

        $leadEmails = collect($validated['lead_emails'] ?? []);

        $recipients = $leadEmails->merge($extraEmails)->unique()->values()
            ->map(fn ($email) => ['email' => $email])
            ->all();

        if (empty($recipients)) {
            return back()->withErrors(['lead_emails' => 'Select at least one lead or add an email address.'])->withInput();
        }

        $admin = Auth::guard('admin')->user();
        $publicUrl = $property->slug ? route('property-details', $property) : url('/');

        $sentCount = $this->edmSender->send(
            property: $property,
            subject: $validated['subject'],
            message: $validated['message'],
            recipients: $recipients,
            publicUrl: $publicUrl,
            senderName: $admin?->name,
            senderEmail: $admin?->email,
            senderPhone: null,
            senderType: 'admin',
            senderId: $admin?->id,
        );

        return redirect()
            ->route('admin.properties.marketing.edm', $property->id)
            ->with('edm_success', "Email campaign sent to {$sentCount} recipient(s).");
    }
}
