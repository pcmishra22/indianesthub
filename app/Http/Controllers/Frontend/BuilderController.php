<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\BuilderLead;
use App\Models\BuilderView;
use App\Models\ProjectView;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BuilderController extends Controller
{
    /**
     * Public builder listing page (/builders)
     */
    public function index(Request $request)
    {
        $query = Builder::withCount('projects')
            ->where('status', 'active')
            ->orderByDesc('projects_count');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('company_name', 'like', "%{$s}%")
                  ->orWhere('name',         'like', "%{$s}%")
                  ->orWhere('city',         'like', "%{$s}%")
                  ->orWhere('cities_operating', 'like', "%{$s}%");
            });
        }

        if ($request->filled('city')) {
            $city = $request->city;
            $query->where(function ($q) use ($city) {
                $q->where('city', 'like', "%{$city}%")
                  ->orWhere('cities_operating', 'like', "%{$city}%");
            });
        }

        $builders = $query->paginate(12)->withQueryString();

        $totalBuilders   = Builder::where('status', 'active')->count();
        $totalProjects   = BuilderProject::where('is_active', true)->count();
        $verifiedCount   = Builder::where('is_verified', true)->count();

        return view('frontend.builders', compact('builders', 'totalBuilders', 'totalProjects', 'verifiedCount'));
    }

    /**
     * Public builder profile page (/builders/{builder})
     */
    public function show(Builder $builder)
    {
        abort_if($builder->status !== 'active', 404);

        // Mask builder contact details if the user is not logged in
        if (!Auth::check()) {
            $builder->phone = 'Login to view';
            $builder->email = 'Login to view';
        }

        $builder->loadCount('projects');

        $projects = $builder->projects()
            ->active()
            ->withCount('properties')
            ->latest()
            ->paginate(6);

        $totalUnits = $builder->projects()->active()->sum('total_units');

        $citiesServed = $builder->projects()
            ->active()
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->unique()
            ->values();

        // Track this visit (who visited the builder page, guest or logged-in)
        BuilderView::create([
            'builder_id'    => $builder->id,
            'event_type'    => 'page_view',
        ] + $this->visitContext());

        return view('frontend.builder-profile', compact('builder', 'projects', 'totalUnits', 'citiesServed'));
    }

    /**
     * Public project detail page (/projects/{project})
     */
    public function projectDetail(BuilderProject $project)
    {
        abort_if(!$project->is_active, 404);

        $project->load(['builder', 'amenityItems', 'properties' => function ($q) {
            $q->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired'])
              ->orderBy('price');
        }]);

        // Mask builder contact details if the user is not logged in
        if (!Auth::check() && $project->builder) {
            $project->builder->phone = 'Login to view';
            $project->builder->email = 'Login to view';
        }

        // Increment views
        $project->increment('views_count');

        // Track this visit (who visited the project page, guest or logged-in)
        ProjectView::create([
            'builder_project_id' => $project->id,
            'event_type'         => 'page_view',
        ] + $this->visitContext());

        // Grouped amenities
        $amenitiesByCategory = $project->amenityItems->groupBy('category');

        // Price summary
        $minPrice = $project->properties->min('price') ?? $project->price_from;
        $maxPrice = $project->properties->max('price') ?? $project->price_to;

        // Other projects by same builder (exclude current)
        $relatedProjects = BuilderProject::where('builder_id', $project->builder_id)
            ->where('id', '!=', $project->id)
            ->withCount('properties')
            ->latest()
            ->limit(3)
            ->get();

        return view('frontend.project-detail', compact(
            'project', 'amenitiesByCategory', 'minPrice', 'maxPrice', 'relatedProjects'
        ));
    }

    /**
     * Handle lead form submission from project detail page (AJAX / POST)
     */
    public function submitLead(Request $request, BuilderProject $project)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['nullable', 'email', 'max:150'],
            'phone'     => ['required', 'string', 'max:20'],
            'message'   => ['nullable', 'string', 'max:1000'],
            'lead_type' => ['required', 'in:general,visit,callback,brochure,whatsapp'],
        ]);

        $lead = BuilderLead::create([
            'builder_id'         => $project->builder_id,
            'builder_project_id' => $project->id,
            'name'               => $validated['name'],
            'email'              => $validated['email'] ?? null,
            'phone'              => $validated['phone'],
            'message'            => $validated['message'] ?? null,
            'lead_type'          => $validated['lead_type'],
            'source'             => 'website',
            'status'             => 'new',
            'ip_address'         => $request->ip(),
            'user_agent'         => $request->userAgent(),
            'visitor_token'      => $request->cookie('visitor_token'),
        ]);

        // Compute initial hot score
        $lead->recomputeHotScore();

        // Update leads count on project
        $project->increment('leads_count');

        // Handle Notifications
        try {
            $this->sendLeadNotifications($lead, $project);
        } catch (\Exception $e) {
            Log::error("Builder lead notification failed: " . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Your enquiry has been submitted. We will contact you shortly!']);
        }

        return back()->with('success', 'Your enquiry has been submitted. We will contact you shortly!');
    }

    /**
     * Send email and WhatsApp notifications for new builder leads.
     */
    private function sendLeadNotifications(BuilderLead $lead, BuilderProject $project): void
    {
        $builder = $project->builder;
        $messageText = "New Lead for Project: {$project->name}\n" .
                       "Name: {$lead->name}\n" .
                       "Email: {$lead->email}\n" .
                       "Phone: {$lead->phone}\n" .
                       "Type: {$lead->lead_type}\n" .
                       "Message: {$lead->message}";

        Log::info("Processing lead notifications for Lead ID: {$lead->id}");

        $fromAddress = config('mail.from.address', 'admin@indianesthub.com');
        $fromName = config('mail.from.name', 'India Nest Hub');

        // NOTE: This is an IndianestHub lead. It must go to IndianestHub only —
        // never to the builder's own email or WhatsApp number.

        // 1. Notify Admin (CC to both admin emails)
        $adminEmails = ['admin@indianesthub.com', 'pcmishra22@gmail.com'];
        Log::info("Attempting to email admins: " . implode(', ', $adminEmails));
        Mail::raw("Admin Alert - New Builder Lead:\n" . $messageText, function ($message) use ($adminEmails, $fromAddress, $fromName) {
            $message->from($fromAddress, $fromName)
                    ->to($adminEmails[0])
                    ->cc($adminEmails[1])
                    ->subject('New Builder Lead Notification');
        });

        $adminWhatsApp = config('app.whatsapp_number', '7340753780');
        $this->sendWhatsAppNotification($adminWhatsApp, "New lead for project {$project->name}: {$lead->name}");
    }

/**
     * Send WhatsApp notification using the WhatsAppNotificationService.
     */
    private function sendWhatsAppNotification(string $recipientNumber, string $message): void
    {
        $whatsappService = new WhatsAppNotificationService();
        $whatsappService->send($recipientNumber, $message);
    }

    /**
     * Handle inquiry from Builder profile page (no project required)
     * POST /builders/{builder}/inquiry
     */
    public function submitBuilderInquiry(Request $request, Builder $builder)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['nullable', 'email', 'max:150'],
            'phone'     => ['required', 'string', 'max:20'],
            'message'   => ['nullable', 'string', 'max:1000'],
            'lead_type' => ['required', 'in:general,visit,callback,brochure,whatsapp'],
        ]);

        // Save as a BuilderLead (no project)
        $lead = BuilderLead::create([
            'builder_id'         => $builder->id,
            'builder_project_id' => null,
            'name'               => $validated['name'],
            'email'              => $validated['email'] ?? null,
            'phone'              => $validated['phone'],
            'message'            => $validated['message'] ?? null,
            'lead_type'          => $validated['lead_type'],
            'source'             => 'builder_profile',
            'status'             => 'new',
            'ip_address'         => $request->ip(),
            'user_agent'         => $request->userAgent(),
            'visitor_token'      => $request->cookie('visitor_token'),
        ]);

        $lead->recomputeHotScore();

        // Send notifications
        try {
            $fromAddress = config('mail.from.address', 'admin@indianesthub.com');
            $fromName    = config('mail.from.name', 'India Nest Hub');
            $adminEmails = ['admin@indianesthub.com', 'pcmishra22@gmail.com'];

            $messageText = "New Builder Inquiry (Profile Page)\n" .
                           "Builder: {$builder->company_name}\n" .
                           "Name: {$lead->name}\n" .
                           "Email: {$lead->email}\n" .
                           "Phone: {$lead->phone}\n" .
                           "Type: {$lead->lead_type}\n" .
                           "Message: {$lead->message}";

            // NOTE: This is an IndianestHub lead. It must go to IndianestHub only —
            // never to the builder's own email or WhatsApp number.

            // Notify Admins
            Mail::raw("Admin Alert — Builder Profile Inquiry:\n" . $messageText, function ($message) use ($adminEmails, $fromAddress, $fromName) {
                $message->from($fromAddress, $fromName)
                        ->to($adminEmails[0])
                        ->cc($adminEmails[1])
                        ->subject('New Builder Profile Inquiry');
            });

            // WhatsApp to IndianestHub admin (never the builder)
            $adminWhatsApp = config('app.whatsapp_number', '7340753780');
            $this->sendWhatsAppNotification($adminWhatsApp, "New inquiry for builder {$builder->company_name}: {$lead->name}, {$lead->phone}");

        } catch (\Exception $e) {
            Log::error("Builder inquiry notification failed: " . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Your enquiry has been submitted. We will contact you shortly!']);
        }

        return back()->with('success', 'Your enquiry has been submitted. We will contact you shortly!');
    }

    /**
     * Common visitor tracking fields (guest fingerprint, device, browser,
     * referrer...) shared by BuilderView and ProjectView inserts. Reuses
     * the same "visitor_token" cookie already used for property tracking
     * so admin can follow one visitor across properties/builders/projects.
     */
    private function visitContext(): array
    {
        $request = request();
        $userAgent = $request->userAgent() ?? '';

        $visitorToken = $request->cookie('visitor_token');
        if (empty($visitorToken)) {
            $visitorToken = (string) \Illuminate\Support\Str::uuid();
            Cookie::queue('visitor_token', $visitorToken, 60 * 24 * 30); // ~30 days
        }

        return [
            'user_id'       => Auth::id(),
            'session_id'    => $request->session()->getId(),
            'visitor_token' => $visitorToken,
            'ip_address'    => $request->ip(),
            'device'        => $this->detectDevice($userAgent),
            'browser'       => $this->detectBrowser($userAgent),
            'referrer'      => $request->headers->get('referer'),
            'page_url'      => $request->fullUrl(),
            'viewed_at'     => now(),
        ];
    }

    private function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function detectBrowser(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'edg/'))    return 'Edge';
        if (str_contains($ua, 'opr/') || str_contains($ua, 'opera')) return 'Opera';
        if (str_contains($ua, 'chrome'))   return 'Chrome';
        if (str_contains($ua, 'firefox'))  return 'Firefox';
        if (str_contains($ua, 'safari'))   return 'Safari';
        if (str_contains($ua, 'msie') || str_contains($ua, 'trident')) return 'IE';
        return 'Other';
    }
}
