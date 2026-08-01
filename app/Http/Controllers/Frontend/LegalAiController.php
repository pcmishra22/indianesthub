<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\AiLegalChecklistService;
use Illuminate\Http\Request;

class LegalAiController extends Controller
{
    public function generateChecklist(Request $request, AiLegalChecklistService $ai)
    {
        $attrs = $request->validate([
            'issue_type'    => 'required|string|max:50',
            'property_type' => 'nullable|string|max:100',
            'city'          => 'nullable|string|max:100',
            'buyer_type'    => 'nullable|string|max:50',
        ]);

        $result = $ai->generate($attrs);

        if ($result['error']) {
            return response()->json(['error' => true, 'message' => $result['message']], 422);
        }

        return response()->json([
            'error' => false,
            'documents' => $result['documents'],
            'steps' => $result['steps'],
            'red_flags' => $result['red_flags'],
        ]);
    }
}
