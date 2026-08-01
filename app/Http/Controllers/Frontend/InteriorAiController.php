<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\AiInteriorSuggestionsService;
use Illuminate\Http\Request;

class InteriorAiController extends Controller
{
    public function index()
    {
        return view('frontend.interior-suggestions');
    }

    public function generate(Request $request, AiInteriorSuggestionsService $ai)
    {
        $attrs = $request->validate([
            'room_type' => 'required|string|max:100',
            'style'     => 'nullable|string|max:100',
            'budget'    => 'nullable|string|max:100',
            'bhk_type'  => 'nullable|string|max:50',
        ]);

        $result = $ai->generate($attrs);

        if ($result['error']) {
            return response()->json(['error' => true, 'message' => $result['message']], 422);
        }

        return response()->json([
            'error' => false,
            'suggestions' => $result['suggestions'],
            'products' => $result['products']->map(function ($p) {
                return [
                    'name' => $p->name,
                    'url' => route('marketplace.product', [$p->category->slug, $p->slug]),
                    'image' => $p->cover_image ? $p->cover_url : null,
                    'price_label' => $p->price_label,
                    'category' => $p->category->name,
                ];
            })->values(),
        ]);
    }
}
