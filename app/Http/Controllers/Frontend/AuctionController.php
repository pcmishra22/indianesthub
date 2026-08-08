<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\AuctionBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuctionController extends Controller
{
    /**
     * Public listing of live + upcoming auctions.
     */
    public function index(Request $request)
    {
        $query = Auction::with(['property'])
            ->whereIn('status', [Auction::STATUS_LIVE, Auction::STATUS_APPROVED])
            ->orderByRaw("status = 'live' desc") // live first, then upcoming
            ->orderBy('end_at');

        if ($request->filled('city')) {
            $query->whereHas('property', fn ($q) => $q->where('city', $request->city));
        }

        $auctions = $query->paginate(12)->withQueryString();

        $endedAuctions = Auction::with('property')
            ->whereIn('status', [Auction::STATUS_WINNER_CONFIRMED, Auction::STATUS_COMPLETED])
            ->orderByDesc('end_at')
            ->take(6)
            ->get();

        return view('frontend.auctions.index', compact('auctions', 'endedAuctions'));
    }

    /**
     * Auction detail page — countdown, bid form, bid history.
     */
    public function show(Auction $auction)
    {
        $auction->load(['property', 'sellerUser', 'currentHighestBidder', 'documents' => fn ($q) => $q->where('status', 'approved')]);

        $bids = $auction->bids()->with('bidder')->take(20)->get();

        $user = Auth::user();
        $canBid = false;
        $blockReason = null;

        if (! $user) {
            $blockReason = 'login';
        } elseif ((int) $auction->seller_user_id === (int) $user->id) {
            $blockReason = 'self';
        } elseif (! $user->hasVerifiedKyc()) {
            $blockReason = 'kyc';
        } elseif (! $user->hasVerifiedDepositFor($auction->id)) {
            $blockReason = 'deposit';
        } elseif (! $auction->isLive()) {
            $blockReason = 'not_live';
        } else {
            $canBid = true;
        }

        // Once the seller has accepted the bid or opted to negotiate, both
        // sides' contact details unlock so they can move the sale forward
        // offline — before that point, everything stays anonymous.
        $revealContact = $user && (int) $auction->current_highest_bidder_id === (int) $user->id
            && in_array($auction->status, [Auction::STATUS_WINNER_CONFIRMED, Auction::STATUS_PENDING_SELLER_DECISION])
            && in_array($auction->seller_decision, [Auction::DECISION_ACCEPTED, Auction::DECISION_NEGOTIATING]);

        return view('frontend.auctions.show', compact('auction', 'bids', 'canBid', 'blockReason', 'revealContact'));
    }

    /**
     * Place a bid. Runs inside a DB transaction with row locking so two
     * simultaneous bids can't both "win" — the second one gets re-validated
     * against the just-updated highest bid before it's accepted.
     */
    public function placeBid(Request $request, Auction $auction)
    {
        $user = Auth::user();

        if (! $user) {
            return back()->with('error', 'Please log in to place a bid.');
        }
        if ((int) $auction->seller_user_id === (int) $user->id) {
            return back()->with('error', 'You cannot bid on your own auction.');
        }
        if (! $user->hasVerifiedKyc()) {
            return back()->with('error', 'Please complete KYC verification before bidding.');
        }
        if (! $user->hasVerifiedDepositFor($auction->id)) {
            return back()->with('error', 'Please pay the refundable bid deposit for this auction before bidding.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated, $auction, $user, $request) {
            /** @var Auction $locked */
            $locked = Auction::where('id', $auction->id)->lockForUpdate()->firstOrFail();

            if (! $locked->isLive()) {
                return back()->with('error', 'This auction is not currently accepting bids.');
            }

            $minBid = $locked->minimumNextBid();
            if ((float) $validated['amount'] < $minBid) {
                return back()->with('error', 'Your bid must be at least ₹' . number_format($minBid) . '.');
            }
            if ((int) $locked->current_highest_bidder_id === (int) $user->id) {
                return back()->with('error', 'You are already the highest bidder.');
            }

            // Anti-sniping: if a bid lands within the last 5 minutes, push the
            // end time out by 5 more minutes so no one gets sniped at :00.
            $newEndAt = $locked->end_at;
            $extensionCount = $locked->extension_count;
            if ($locked->end_at && now()->diffInMinutes($locked->end_at, false) <= 5) {
                $newEndAt = $locked->end_at->addMinutes(5);
                $extensionCount++;
            }

            AuctionBid::where('auction_id', $locked->id)->update(['is_winning' => false]);

            $bid = AuctionBid::create([
                'auction_id'  => $locked->id,
                'user_id'     => $user->id,
                'amount'      => $validated['amount'],
                'is_winning'  => true,
                'ip_address'  => $request->ip(),
            ]);

            $locked->update([
                'current_highest_bid'       => $validated['amount'],
                'current_highest_bidder_id' => $user->id,
                'end_at'                    => $newEndAt,
                'extension_count'           => $extensionCount,
            ]);

            return back()->with('success', 'Bid placed! You are currently the highest bidder.');
        });
    }
}
