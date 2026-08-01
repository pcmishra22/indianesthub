<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\AiInvestmentAdvisorService;
use Illuminate\Http\Request;

class InvestmentAiController extends Controller
{
    public function index()
    {
        return view('frontend.investment-advisor');
    }

    public function analyze(Request $request, AiInvestmentAdvisorService $ai)
    {
        $attrs = $request->validate([
            'city'     => 'required|string|max:100',
            'budget'   => 'required|numeric|min:1',
            'bhk_type' => 'nullable|string|max:50',
            'goal'     => 'nullable|in:rental,appreciation,both',
            'horizon'  => 'nullable|string|max:50',
        ]);

        $result = $ai->analyze($attrs);

        if ($result['error']) {
            return response()->json(['error' => true, 'message' => $result['message']], 422);
        }

        return response()->json($result);
    }
}
