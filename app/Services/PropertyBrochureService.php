<?php

namespace App\Services;

use App\Models\Property;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PropertyBrochureService
{
    /**
     * Render the brochure PDF binary for a property (does not save anything).
     */
    public function render(Property $property): \Barryvdh\DomPDF\PDF
    {
        $property->loadMissing(['dealer', 'builder', 'builderProject']);

        $publicUrl = null;
        if ($property->slug) {
            $publicUrl = route('property-details', $property);
        }

        $galleryImages = collect($property->gallery_images ?? [])->take(5)->values();

        $amenities = collect(
            is_array($property->amenities)
                ? $property->amenities
                : array_filter(array_map('trim', explode(',', (string) $property->amenities)))
        )->filter()->values();

        $contactName  = $property->contact_name
            ?: optional($property->dealer)->first_name . ' ' . optional($property->dealer)->last_name
            ?: optional($property->builder)->name;
        $contactPhone = $property->contact_phone ?: optional($property->dealer)->phone ?: optional($property->builder)->phone;
        $companyName  = $property->company_name ?: optional($property->dealer)->company_name ?: optional($property->builder)->company_name;

        $data = [
            'property'      => $property,
            'publicUrl'     => $publicUrl,
            'galleryImages' => $galleryImages,
            'amenities'     => $amenities,
            'contactName'   => trim((string) $contactName),
            'contactPhone'  => $contactPhone,
            'companyName'   => $companyName,
            'generatedAt'   => now(),
        ];

        return Pdf::loadView('marketing.brochure-pdf', $data)->setPaper('a4', 'portrait');
    }

    /**
     * Render and persist the brochure to public storage, updating the
     * property's brochure_pdf column. Returns the storage path.
     */
    public function generateAndStore(Property $property): string
    {
        $pdf = $this->render($property);

        $folder = $property->builder_id
            ? "builder/{$property->builder_id}/brochures"
            : "dealer/{$property->property_dealer_id}/brochures";

        $filename = 'brochure-' . $property->slug . '-' . now()->timestamp . '.pdf';
        $path = $folder . '/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        // Clean up the previous auto-generated brochure so we don't
        // accumulate files, but leave manually-uploaded ones (best effort).
        if ($property->brochure_pdf && str_contains($property->brochure_pdf, '/brochures/brochure-')) {
            Storage::disk('public')->delete($property->brochure_pdf);
        }

        $property->update(['brochure_pdf' => $path]);

        return $path;
    }

    /**
     * Suggested filename shown to the user on download.
     */
    public function downloadFilename(Property $property): string
    {
        return \Illuminate\Support\Str::slug($property->title ?: 'property') . '-brochure.pdf';
    }
}
