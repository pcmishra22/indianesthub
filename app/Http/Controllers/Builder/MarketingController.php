<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\BuilderProject;
use App\Models\Property;
use App\Services\PropertyBrochureService;
use App\Services\SocialPostContentService;
use Illuminate\Support\Facades\Auth;

class MarketingController extends Controller
{
    public function __construct(
        protected PropertyBrochureService $brochures,
        protected SocialPostContentService $socialContent,
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
}
