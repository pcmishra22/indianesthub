<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\Property;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class DealerController extends Controller
{
    /* ------------------------------------------------------------------ */
    /*  INDEX — list with search + filter + pagination                      */
    /* ------------------------------------------------------------------ */
    public function index(Request $request)
    {
        $query = Dealer::withCount('properties')
                       ->with('subscription');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name',   'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('email',      'like', "%{$search}%")
                  ->orWhere('phone',      'like', "%{$search}%")
                  ->orWhere('company_name','like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Sort
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest'     => $query->oldest(),
            'name'       => $query->orderBy('first_name'),
            'properties' => $query->orderByDesc('properties_count'),
            default      => $query->latest(),
        };

        $dealers = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total'    => Dealer::count(),
            'active'   => Dealer::where('status', 'active')->count(),
            'inactive' => Dealer::where('status', 'inactive')->count(),
            'blocked'  => Dealer::where('status', 'blocked')->count(),
            'with_sub' => Subscription::where('status', 'active')->distinct('property_dealer_id')->count('property_dealer_id'),
        ];

        return view('backend.dealers.index', compact('dealers', 'stats'));
    }

    /* ------------------------------------------------------------------ */
    /*  SHOW                                                                */
    /* ------------------------------------------------------------------ */
    public function show(Dealer $dealer)
    {
        $dealer->load('subscription');
        $properties = $dealer->properties()
                             ->latest()
                             ->paginate(10);
        $propStats = [
            'total'    => $dealer->properties()->count(),
            'active'   => $dealer->properties()->where('status', 'active')->count(),
            'inactive' => $dealer->properties()->where('status', '!=', 'active')->count(),
        ];

        return view('backend.dealers.show', compact('dealer', 'properties', 'propStats'));
    }

    /* ------------------------------------------------------------------ */
    /*  CREATE                                                              */
    /* ------------------------------------------------------------------ */
    public function create()
    {
        return view('backend.dealers.create');
    }

    /* ------------------------------------------------------------------ */
    /*  STORE                                                               */
    /* ------------------------------------------------------------------ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'nullable|string|max:100',
            'email'            => 'required|email|unique:property_dealers,email',
            'phone'            => 'required|string|max:20',
            'company_name'     => 'nullable|string|max:200',
            'password'         => ['required', Password::min(8)],
            'status'           => 'required|in:active,inactive,blocked',
            'bio'              => 'nullable|string|max:1000',
            'specializations'  => 'nullable|string',
            'operating_cities' => 'nullable|string',
        ]);

        // Convert comma-separated to arrays
        $validated['specializations']  = $this->parseTagInput($request->input('specializations'));
        $validated['operating_cities'] = $this->parseTagInput($request->input('operating_cities'));
        $validated['password']         = Hash::make($validated['password']);

        $dealer = Dealer::create($validated);

        return redirect()
            ->route('admin.dealers.show', $dealer)
            ->with('success', 'Dealer "' . $dealer->name . '" created successfully.');
    }

    /* ------------------------------------------------------------------ */
    /*  EDIT                                                                */
    /* ------------------------------------------------------------------ */
    public function edit(Dealer $dealer)
    {
        return view('backend.dealers.edit', compact('dealer'));
    }

    /* ------------------------------------------------------------------ */
    /*  UPDATE                                                              */
    /* ------------------------------------------------------------------ */
    public function update(Request $request, Dealer $dealer)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'nullable|string|max:100',
            'email'            => 'required|email|unique:property_dealers,email,' . $dealer->id,
            'phone'            => 'required|string|max:20',
            'company_name'     => 'nullable|string|max:200',
            'password'         => ['nullable', Password::min(8)],
            'status'           => 'required|in:active,inactive,blocked',
            'bio'              => 'nullable|string|max:1000',
            'specializations'  => 'nullable|string',
            'operating_cities' => 'nullable|string',
        ]);

        $validated['specializations']  = $this->parseTagInput($request->input('specializations'));
        $validated['operating_cities'] = $this->parseTagInput($request->input('operating_cities'));

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $dealer->update($validated);

        return redirect()
            ->route('admin.dealers.show', $dealer)
            ->with('success', 'Dealer updated successfully.');
    }

    /* ------------------------------------------------------------------ */
    /*  DESTROY                                                             */
    /* ------------------------------------------------------------------ */
    public function destroy(Dealer $dealer)
    {
        $name = $dealer->name;

        // Nullify property ownership before deleting
        Property::where('property_dealer_id', $dealer->id)
                ->update(['property_dealer_id' => null]);

        // Delete subscription if any
        Subscription::where('property_dealer_id', $dealer->id)->delete();

        $dealer->delete();

        return redirect()
            ->route('admin.dealers.index')
            ->with('success', "Dealer \"{$name}\" has been deleted.");
    }

    /* ------------------------------------------------------------------ */
    /*  TOGGLE STATUS                                                       */
    /* ------------------------------------------------------------------ */
    public function toggleStatus(Request $request, Dealer $dealer)
    {
        $newStatus = match ($dealer->status) {
            'active'   => 'blocked',
            'blocked'  => 'active',
            'inactive' => 'active',
            default    => 'active',
        };

        $dealer->update(['status' => $newStatus]);

        $label = ucfirst($newStatus);
        return back()->with('success', "Dealer status changed to \"{$label}\".");
    }

    /* ------------------------------------------------------------------ */
    /*  Helper                                                              */
    /* ------------------------------------------------------------------ */
    private function parseTagInput(?string $input): array
    {
        if (blank($input)) return [];
        return array_values(array_filter(array_map('trim', explode(',', $input))));
    }
}
