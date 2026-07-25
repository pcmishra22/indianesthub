<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\BuilderProject;
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

    private function authorizeProperty(BuilderProject $project, Property $property): void
    {
        if ($project->builder_id !== Auth::guard('builder')->id()) {
            abort(403, 'Unauthorized action.');
        }
        if ($property->builder_project_id !== $project->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Marketing Studio hub for a single unit/property.
     */
    public function index(BuilderProject $project, Property $property)
    {
        $this->authorizeProperty($project, $property);

        $publicUrl = $property->slug ? route('property-details', $property) : null;

        return view('builder.properties.marketing', compact('project', 'property', 'publicUrl'));
    }

    /**
     * Generate the brochure PDF and stream it for download.
     * Also saves a copy against the property's brochure_pdf field.
     */
    public function brochure(BuilderProject $project, Property $property)
    {
        $this->authorizeProperty($project, $property);

        $this->brochures->generateAndStore($property);

        return $this->brochures->render($property)
            ->download($this->brochures->downloadFilename($property));
    }

    /**
     * Social media post generator (canvas-based image + caption).
     */
    public function socialPost(BuilderProject $project, Property $property)
    {
        $this->authorizeProperty($project, $property);

        $publicUrl = $property->slug ? route('property-details', $property) : null;
        $caption = $this->socialContent->caption($property, $publicUrl);

        return view('builder.properties.social-post', compact('project', 'property', 'publicUrl', 'caption'));
    }

    /**
     * EDM composer: shows leads who enquired about this unit + subject/message form.
     */
    public function edm(BuilderProject $project, Property $property)
    {
        $this->authorizeProperty($project, $property);

        $leads = Inquiry::where('property_id', $property->id)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'email'])
            ->unique('email')
            ->values();

        $subject = $this->edmContent->defaultSubject($property);
        $message = $this->edmContent->defaultMessage($property);
        $sendUrl = route('builder.projects.properties.marketing.edm.send', [$project, $property]);

        return view('builder.properties.edm', compact('project', 'property', 'leads', 'subject', 'message', 'sendUrl'));
    }

    /**
     * Send the EDM campaign to selected leads + manually entered emails.
     */
    public function sendEdm(Request $request, BuilderProject $project, Property $property)
    {
        $this->authorizeProperty($project, $property);

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

        $builder = Auth::guard('builder')->user();
        $publicUrl = $property->slug ? route('property-details', $property) : url('/');

        $sentCount = $this->edmSender->send(
            property: $property,
            subject: $validated['subject'],
            message: $validated['message'],
            recipients: $recipients,
            publicUrl: $publicUrl,
            senderName: $builder?->name,
            senderEmail: $builder?->email,
            senderPhone: $builder?->phone,
            senderType: 'builder',
            senderId: $builder?->id,
        );

        return redirect()
            ->route('builder.projects.properties.marketing.edm', [$project, $property])
            ->with('edm_success', "Email campaign sent to {$sentCount} recipient(s).");
    }
}
