<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuctionKycController extends Controller
{
    public function index()
    {
        $pending = User::where('kyc_status', 'pending')->orderBy('kyc_submitted_at')->paginate(20);
        return view('backend.auction-kyc.index', compact('pending'));
    }

    public function approve(User $user)
    {
        $user->update([
            'kyc_status'      => 'verified',
            'kyc_verified_at' => now(),
        ]);

        return back()->with('success', "{$user->name}'s KYC verified.");
    }

    public function reject(Request $request, User $user)
    {
        $validated = $request->validate(['reason' => 'required|string|max:500']);

        $user->update([
            'kyc_status'            => 'rejected',
            'kyc_rejection_reason'  => $validated['reason'],
        ]);

        return back()->with('success', "{$user->name}'s KYC rejected.");
    }
}
