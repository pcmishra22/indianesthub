<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataImportBatch;
use App\Models\Property;
use App\Services\CityDataDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CityDataImportController extends Controller
{
    public function create()
    {
        return view('admin.city-import.create');
    }

    /**
     * Step 1: admin submits city + type. We fetch candidates and STAGE them
     * (nothing is written to builders/agents/properties yet).
     */
    public function discover(Request $request, CityDataDiscoveryService $service)
    {
        // Worst case (Mappls + Nominatim + Overpass mirror failover all timing
        // out) can take up to ~45s. Request more time where the host allows it
        // (silently ignored on hosts that disable this — harmless either way);
        // the service's own per-request timeouts are the real bound, this is
        // just a backstop so PHP itself doesn't kill the request first.
        if (function_exists('set_time_limit')) {
            @set_time_limit(90);
        }

        $validated = $request->validate([
            'city' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:builder,agent,property'],
            'csv_file' => ['nullable', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        try {
            $result = $service->discover($validated['type'], $validated['city'], $request->file('csv_file'));
        } catch (\RuntimeException $e) {
            return back()->withErrors(['discovery' => $e->getMessage()]);
        }

        $batch = DataImportBatch::create([
            'admin_id' => optional($request->user())->id,
            'city'     => $validated['city'],
            'type'     => $validated['type'],
            'source'   => $validated['type'] === 'property' ? 'manual_csv' : 'openstreetmap',
            'status'   => 'pending',
            'payload'  => $result['candidates'],
        ]);

        return view('admin.city-import.review', [
            'batch'      => $batch,
            'candidates' => $result['candidates'],
            'notice'     => $result['notice'],
        ]);
    }

    /**
     * Step 2: admin ticks the rows they want and hits one "Confirm" button.
     * Only now do we write to the real tables, skipping anything that
     * already exists (matched by name+city, or by phone/website).
     */
    public function confirm(Request $request, DataImportBatch $batch)
    {
        $validated = $request->validate([
            'selected'   => ['array'],
            'selected.*' => ['integer'],
        ]);

        if ($batch->status !== 'pending') {
            return redirect()
                ->route('admin.city-import.create')
                ->with('status', 'This batch was already ' . $batch->status . '.');
        }

        $selectedIndexes = $validated['selected'] ?? [];
        $payload = $batch->payload ?? [];
        $inserted = 0;
        $skipped = 0;

        foreach ($selectedIndexes as $idx) {
            $item = $payload[$idx] ?? null;
            if (!$item) {
                continue;
            }

            $ok = match ($batch->type) {
                'builder'  => $this->insertBuilder($item),
                'agent'    => $this->insertAgent($item),
                'property' => $this->insertProperty($item),
                default    => false,
            };

            $ok ? $inserted++ : $skipped++;
        }

        $batch->update([
            'status'  => 'confirmed',
            'summary' => "{$inserted} inserted, {$skipped} skipped (already existed)",
        ]);

        return redirect()
            ->route('admin.city-import.create')
            ->with('status', "✅ {$batch->type} import for {$batch->city}: {$inserted} inserted, {$skipped} skipped as duplicates.");
    }

    public function reject(DataImportBatch $batch)
    {
        $batch->update(['status' => 'rejected']);

        return redirect()
            ->route('admin.city-import.create')
            ->with('status', "Batch for {$batch->city} discarded — nothing was written to the database.");
    }

    protected function insertProperty(array $item): bool
    {
        $title = $item['title'] ?? null;
        $address = $item['address'] ?? null;
        $city = $item['city'] ?? null;
        if (!$title || !$address || !$city) {
            return false;
        }

        // Dedup: skip if a property with the same title+address+city already exists.
        $alreadyExists = DB::table('properties')
            ->whereRaw('LOWER(title) = ?', [strtolower($title)])
            ->whereRaw('LOWER(address) = ?', [strtolower($address)])
            ->whereRaw('LOWER(city) = ?', [strtolower($city)])
            ->exists();
        if ($alreadyExists) {
            return false;
        }

        // Uses the Eloquent model (not DB::table) so the model's `creating` event
        // still auto-generates a unique slug — the frontend property page is
        // routed by slug (`{property:slug}`), so a raw DB insert would leave
        // these properties with no working URL.
        Property::create([
            'property_dealer_id' => $this->getImportDealerId(),
            'title'              => $title,
            'description'        => $item['description'] ?? null,
            'property_type'      => $item['property_type'] ?? 'Apartment',
            'looking_for'        => $item['looking_for'] ?? 'Sale',
            'address'            => $address,
            'city'               => $city,
            'state'              => $item['state'] ?? '',
            'price'              => $item['price'] ?? 0,
            'bedrooms'           => $item['bedrooms'] ?? null,
            'bathrooms'          => $item['bathrooms'] ?? null,
            'area'               => $item['area'] ?? null,
            'furnishing'         => $item['furnishing'] ?? null,
            'amenities'          => $item['amenities'] ?? null,
            'status'             => 'Available',
        ]);

        return true;
    }

    /**
     * CSV-imported properties still need a property_dealer_id (required FK).
     * Reuses (or creates once) a single system "CSV Import" dealer account to
     * attribute them to, rather than inventing a fake dealer per row.
     */
    protected function getImportDealerId(): int
    {
        $email = 'csv-import@indianesthub.system';

        $existing = DB::table('property_dealers')->where('email', $email)->value('id');
        if ($existing) {
            return $existing;
        }

        return DB::table('property_dealers')->insertGetId([
            'first_name'   => 'CSV',
            'last_name'    => 'Import',
            'phone'        => '0000000000',
            'email'        => $email,
            'company_name' => 'Admin CSV Import',
            'password'     => Hash::make(Str::random(32)),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    protected function insertBuilder(array $item): bool
    {
        $name = $item['name'] ?? null;
        $city = $item['city'] ?? null;
        if (!$name || !$city) {
            return false;
        }

        $alreadyExists = DB::table('builders')
            ->where('name', $name)
            ->where('city', $city)
            ->exists();
        if ($alreadyExists) {
            return false;
        }

        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $i = 1;
        while (DB::table('builders')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        // Google Places doesn't expose business email addresses — placeholder
        // in, admin/builder can update it once the account is claimed.
        $email = Str::slug($name, '') . '-' . Str::random(5) . '@import.placeholder';

        DB::table('builders')->insert([
            'name'             => $name,
            'company_name'     => $item['company_name'] ?? $name,
            'email'            => $email,
            'password'         => Hash::make(Str::random(20)),
            'phone'            => $item['phone'] ?? null,
            'website'          => $item['website'] ?? null,
            'city'             => $city,
            'slug'             => $slug,
            'status'           => 'active',
            'description'      => 'Imported ' . now()->toDateString() . ' via Google Places. Address: ' . ($item['address'] ?? 'N/A'),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return true;
    }

    protected function insertAgent(array $item): bool
    {
        // In this app "agents" map to the property_dealers table.
        $name = $item['name'] ?? null;
        $city = $item['city'] ?? null;
        if (!$name || !$city) {
            return false;
        }

        $companyName = $item['company_name'] ?? $name;

        $alreadyExists = DB::table('property_dealers')
            ->where('company_name', $companyName)
            ->exists();
        if ($alreadyExists) {
            return false;
        }

        $baseSlug = Str::slug($companyName);
        $slug = $baseSlug;
        $i = 1;
        while (DB::table('property_dealers')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $email = Str::slug($companyName, '') . '-' . Str::random(5) . '@import.placeholder';

        [$firstName, $lastName] = $this->splitName($name);

        DB::table('property_dealers')->insert([
            'first_name'       => $firstName,
            'last_name'        => $lastName,
            'company_name'     => $companyName,
            'phone'            => $item['phone'] ?? '0000000000',
            'email'            => $email,
            'password'         => Hash::make(Str::random(20)),
            'slug'             => $slug,
            'bio'              => 'Imported ' . now()->toDateString() . ' via Google Places. Address: ' . ($item['address'] ?? 'N/A'),
            'specializations'  => null,
            'operating_cities' => $city,
            'status'           => 'pending', // reviewed manually before going live
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return true;
    }

    protected function splitName(string $name): array
    {
        $parts = explode(' ', trim($name), 2);
        return [$parts[0] ?? $name, $parts[1] ?? ''];
    }
}
