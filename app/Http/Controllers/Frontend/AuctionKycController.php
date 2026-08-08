<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionKycController extends Controller
{
    /**
     * One-time KYC form (PAN + ID proof). Verification is manual, reviewed
     * by admin — same trust model as the rest of this platform's document
     * checks, no third-party PAN verification API wired up yet.
     */
    public function create()
    {
        $user = Auth::user();
        return view('frontend.auctions.kyc', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'pan_number' => [
                'required', 'string', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'unique:users,pan_number,' . $user->id,
            ],
            'id_proof'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'pan_number.unique' => 'This PAN is already registered to another account.',
        ]);

        $file = $request->file('id_proof');
        $path = $file->storeAs('kyc/' . $user->id, uniqid() . '.' . $file->getClientOriginalExtension(), 'public');

        $user->update([
            'pan_number'          => strtoupper($validated['pan_number']),
            'kyc_id_proof_path'   => $path,
            'kyc_status'          => 'pending',
            'kyc_submitted_at'    => now(),
            'kyc_verified_at'     => null,
            'kyc_rejection_reason' => null,
        ]);

        return redirect()->route('auctions.kyc')->with('success', 'KYC submitted for review. This usually takes a few hours.');
    }

    /**
     * Deposit payment for a specific auction — manual UPI flow, mirroring
     * the existing dealer listing-payment pattern (Dealer\PropertyController::payProperty).
     * A fixed refundable amount is required per auction before bidding unlocks.
     */
    public function depositForm(Auction $auction)
    {
        $user = Auth::user();
        $depositAmount = $auction->emdAmount();

        $existing = Payment::where('auction_id', $auction->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $upiId = config('services.upi.id', 'indianesthub@upi');
        $upiUrl = "upi://pay?pa={$upiId}&pn=IndianEstHub&am={$depositAmount}&tn=" . urlencode('Auction Deposit - ' . $auction->property->title);

        return view('frontend.auctions.deposit', compact('auction', 'depositAmount', 'existing', 'upiUrl'));
    }

    public function depositSubmit(Request $request, Auction $auction)
    {
        $user = Auth::user();
        $depositAmount = $auction->emdAmount();

        $validated = $request->validate([
            'transaction_id' => 'required|string|max:100',
        ]);

        Payment::create([
            'user_id'         => $user->id,
            'auction_id'      => $auction->id,
            'payment_type'    => 'auction_deposit',
            'amount'          => $depositAmount,
            'status'          => 'pending', // admin verifies against UPI statement, same as dealer listing payments
            'transaction_id'  => $validated['transaction_id'],
            'payment_method'  => 'upi',
        ]);

        return redirect()
            ->route('auctions.show', $auction)
            ->with('success', 'Deposit submitted. Bidding unlocks once our team verifies the payment (usually within a few hours).');
    }
}
