<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\AuctionDocument;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionSubmissionController extends Controller
{
    /**
     * Form to submit a property for auction. Only properties the logged-in
     * user actually owns (via Property.user_id) can be submitted — this
     * keeps the "urgent sale, protect the seller" promise honest.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        $eligibleProperties = Property::where('user_id', $user->id)
            ->whereDoesntHave('auction', fn ($q) => $q->whereNotIn('status', [
                Auction::STATUS_ENDED_UNSOLD, Auction::STATUS_CANCELLED,
            ]))
            ->orderByDesc('created_at')
            ->get();

        $documentTypes = AuctionDocument::TYPES;

        return view('frontend.auctions.submit', compact('eligibleProperties', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'property_id'     => 'required|exists:properties,id',
            'reserve_price'   => 'required|numeric|min:1',
            'starting_bid'    => 'required|numeric|min:0|lte:reserve_price',
            'bid_increment'   => 'nullable|numeric|min:1000',
            'emd_amount'      => 'nullable|numeric|min:1000',
            'duration_days_requested' => 'required|integer|in:3,5,7,10,14',
            'sale_reason'     => 'nullable|string|max:255',
            'sale_reason_public' => 'nullable|boolean',
        ]);

        $property = Property::where('id', $validated['property_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $auction = Auction::create([
            'property_id'    => $property->id,
            'seller_user_id' => $user->id,
            'status'         => Auction::STATUS_SUBMITTED,
            'reserve_price'  => $validated['reserve_price'],
            'starting_bid'   => $validated['starting_bid'],
            'bid_increment'  => $validated['bid_increment'] ?? 10000,
            'emd_amount'     => $validated['emd_amount'] ?? null,
            'duration_days_requested' => $validated['duration_days_requested'],
            'sale_reason'    => $validated['sale_reason'] ?? null,
            'sale_reason_public' => $request->boolean('sale_reason_public'),
        ]);

        return redirect()
            ->route('auctions.submit.documents', $auction)
            ->with('success', 'Auction submitted. Now upload the required documents for admin review.');
    }

    /**
     * Document upload step — separate from the initial form so the seller
     * can come back and add/replace documents if admin requests changes.
     */
    public function documents(Auction $auction)
    {
        $this->authorizeSeller($auction);

        $auction->load('documents');
        $documentTypes = AuctionDocument::TYPES;

        return view('frontend.auctions.documents', compact('auction', 'documentTypes'));
    }

    public function storeDocument(Request $request, Auction $auction)
    {
        $this->authorizeSeller($auction);

        $validated = $request->validate([
            'document_type' => 'required|string|in:' . implode(',', array_keys(AuctionDocument::TYPES)),
            'title'         => 'nullable|string|max:150',
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->storeAs(
            "auctions/{$auction->id}/documents",
            uniqid() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        AuctionDocument::create([
            'auction_id'         => $auction->id,
            'document_type'      => $validated['document_type'],
            'title'              => $validated['title'] ?? null,
            'file_path'          => $path,
            'original_filename'  => $file->getClientOriginalName(),
            'status'             => 'pending',
        ]);

        // Once the seller has uploaded at least the required documents,
        // move the auction into the review queue automatically.
        if ($auction->status === Auction::STATUS_SUBMITTED) {
            $auction->update(['status' => Auction::STATUS_UNDER_REVIEW]);
        }

        return back()->with('success', 'Document uploaded. It will be reviewed by our team shortly.');
    }

    /**
     * Seller's own dashboard of their submitted auctions and statuses.
     */
    public function mine()
    {
        $auctions = Auction::with('property')
            ->where('seller_user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('frontend.auctions.mine', compact('auctions'));
    }

    /**
     * Post-auction decision screen — Accept / Negotiate / Reject / Re-auction,
     * per the spec: the platform never auto-finalizes a sale, the seller
     * always makes the final call once the auction window closes.
     */
    public function decision(Auction $auction)
    {
        $this->authorizeSeller($auction);
        abort_unless($auction->sellerCanDecide(), 404);

        $auction->load(['property', 'currentHighestBidder']);

        return view('frontend.auctions.decision', compact('auction'));
    }

    public function submitDecision(Request $request, Auction $auction)
    {
        $this->authorizeSeller($auction);
        abort_unless($auction->sellerCanDecide(), 404);

        $validated = $request->validate([
            'decision' => 'required|in:' . implode(',', $auction->availableSellerDecisions()),
        ]);

        $auction->update([
            'seller_decision'    => $validated['decision'],
            'seller_decision_at' => now(),
        ]);

        switch ($validated['decision']) {
            case Auction::DECISION_ACCEPTED:
                $auction->update(['status' => Auction::STATUS_WINNER_CONFIRMED]);
                $message = 'Bid accepted. The winning bidder\'s contact details are now visible below — reach out to move ahead with the sale agreement and registration.';
                break;

            case Auction::DECISION_NEGOTIATING:
                // Status stays pending_seller_decision — both parties'
                // contact details unlock so they can negotiate directly.
                $message = 'You\'ve opted to negotiate. The highest bidder\'s contact details are now visible below.';
                break;

            case Auction::DECISION_REJECTED:
                $auction->update(['status' => Auction::STATUS_ENDED_UNSOLD]);
                $message = 'Auction closed as unsold. No further bids will be accepted on this listing.';
                break;

            case Auction::DECISION_REAUCTION:
                // Sent back to "approved" so admin can re-schedule a fresh
                // window, rather than silently reopening with the same bids.
                $auction->update([
                    'status'               => Auction::STATUS_APPROVED,
                    'current_highest_bid'  => null,
                    'current_highest_bidder_id' => null,
                    'start_at'             => null,
                    'end_at'               => null,
                ]);
                $message = 'Re-auction requested. Our team will review and schedule a new bidding window shortly.';
                break;

            default:
                $message = 'Decision recorded.';
        }

        return redirect()->route('auctions.mine')->with('success', $message);
    }

    private function authorizeSeller(Auction $auction): void
    {
        abort_unless($auction->seller_user_id === Auth::id(), 403);
    }
}
