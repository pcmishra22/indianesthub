<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\ServiceProviderPortfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $provider = Auth::guard('service_provider')->user();
        $portfolios = $provider->portfolios()->get();

        return view('service-provider.portfolio', compact('provider', 'portfolios'));
    }

    public function store(Request $request)
    {
        $provider = Auth::guard('service_provider')->user();

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'image'        => 'required|image|max:4096',
            'completed_at' => 'nullable|date',
        ]);

        $validated['service_provider_id'] = $provider->id;
        $validated['image'] = $request->file('image')->store('service-providers/portfolio', 'public');
        $validated['sort_order'] = $provider->portfolios()->count();

        ServiceProviderPortfolio::create($validated);

        return redirect()->route('service-provider.portfolio.index')
            ->with('status', 'Portfolio item added.');
    }

    public function destroy(ServiceProviderPortfolio $portfolio)
    {
        $provider = Auth::guard('service_provider')->user();

        abort_if($portfolio->service_provider_id !== $provider->id, 403);

        if ($portfolio->image) {
            Storage::disk('public')->delete($portfolio->image);
        }
        $portfolio->delete();

        return redirect()->route('service-provider.portfolio.index')
            ->with('status', 'Portfolio item removed.');
    }
}
