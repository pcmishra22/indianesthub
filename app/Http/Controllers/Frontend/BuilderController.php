<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\BuilderLead;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
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
        $totalProjects   = BuilderProject::count();
        $verifiedCount   = Builder::where('is_verified', true)->count();

        return view('frontend.builders', compact('builders', 'totalBuilders', 'totalProjects', 'verifiedCount'));
    }

    /**
     * Public builder profile page (/builders/{builder})
     */
    public function show(Builder $builder)
    {
        abort_if($builder->status !== 'active', 404);

        $builder->loadCount('projects');

        $projects = $builder->projects()
            ->withCount('properties')
            ->latest()
            ->paginate(6);

        $totalUnits = $builder->projects()->sum('total_units');

        $citiesServed = $builder->projects()
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->unique()
            ->values();

        return view('frontend.builder-profile', compact('builder', 'projects', 'totalUnits', 'citiesServed'));
    }

    /**
     * Public project detail page (/projects/{project})
     */
    public function projectDetail(BuilderProject $project)
    {
        $project->load(['builder', 'amenityItems', 'properties' => function ($q) {
            $q->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired'])
              ->orderBy('price');
        }]);

        // Increment views
        $project->increment('views_count');

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
        ]);

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

        $fromAddress = config('mail.from.address', 'support@indianesthub.com');
        $fromName = config('mail.from.name', 'India Nest Hub');

        // 1. Notify Builder
        if ($builder && $builder->email) {
            Log::info("Attempting to email builder: {$builder->email}");
            Mail::raw($messageText, function ($message) use ($builder, $fromAddress, $fromName) {
                $message->from($fromAddress, $fromName)
                        ->to($builder->email)
                        ->subject('New Project Lead Received');
            });
            
            if ($builder->phone) {
                $this->sendWhatsAppNotification($builder->phone, "New lead for your project {$project->name}: {$lead->name}, {$lead->phone}");
            }
        }

        // 2. Notify Admin
        $adminEmail = config('app.contact_email', 'admin@indianesthub.com');
        Log::info("Attempting to email admin: {$adminEmail}");
        Mail::raw("Admin Alert - New Builder Lead:\n" . $messageText, function ($message) use ($adminEmail, $fromAddress, $fromName) {
            $message->from($fromAddress, $fromName)
                    ->to($adminEmail)->subject('New Builder Lead Notification');
        });

        $adminWhatsApp = config('app.whatsapp_number', '9876543210');
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
}
