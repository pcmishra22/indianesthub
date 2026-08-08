<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\AuctionDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::with(['property', 'sellerUser'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $auctions = $query->paginate(20)->withQueryString();
        $statusCounts = Auction::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        return view('backend.auctions.index', compact('auctions', 'statusCounts'));
    }

    public function show(Auction $auction)
    {
        $auction->load(['property', 'sellerUser', 'documents', 'bids.bidder', 'deposits.user']);
        $documentTypes = AuctionDocument::TYPES;

        return view('backend.auctions.show', compact('auction', 'documentTypes'));
    }

    public function approveDocument(Request $request, AuctionDocument $document)
    {
        $document->update([
            'status'               => 'approved',
            'admin_remarks'        => $request->input('remarks'),
            'reviewed_by_admin_id' => Auth::guard('admin')->id(),
            'reviewed_at'          => now(),
        ]);

        return back()->with('success', 'Document approved.');
    }

    public function rejectDocument(Request $request, AuctionDocument $document)
    {
        $validated = $request->validate(['remarks' => 'required|string|max:500']);

        $document->update([
            'status'               => 'rejected',
            'admin_remarks'        => $validated['remarks'],
            'reviewed_by_admin_id' => Auth::guard('admin')->id(),
            'reviewed_at'          => now(),
        ]);

        $document->auction->update(['status' => Auction::STATUS_CHANGES_REQUESTED]);

        return back()->with('success', 'Document rejected — seller has been asked to re-upload.');
    }

    /**
     * Approve the auction as a whole (only possible once the required
     * documents are all individually approved) and schedule its live window.
     */
    public function approve(Request $request, Auction $auction)
    {
        if (! $auction->documentsAllApproved()) {
            return back()->with('error', 'All required documents (sale deed, ownership proof, identity proof) must be approved first.');
        }

        $validated = $request->validate([
            'start_at'       => 'required|date|after_or_equal:now',
            'duration_hours' => 'required|integer|min:1|max:720',
            'emd_amount'     => 'nullable|numeric|min:1000',
            'admin_notes'    => 'nullable|string|max:1000',
        ]);

        $startAt = \Carbon\Carbon::parse($validated['start_at']);
        $endAt = $startAt->copy()->addHours((int) $validated['duration_hours']);

        $auction->update([
            'status'               => Auction::STATUS_APPROVED,
            'start_at'             => $startAt,
            'end_at'               => $endAt,
            'original_end_at'      => $endAt,
            'emd_amount'           => $validated['emd_amount'] ?? $auction->emd_amount,
            'admin_notes'          => $validated['admin_notes'] ?? $auction->admin_notes,
            'reviewed_by_admin_id' => Auth::guard('admin')->id(),
            'reviewed_at'          => now(),
        ]);

        return back()->with('success', 'Auction approved and scheduled. It will go live automatically at the scheduled time.');
    }

    public function reject(Request $request, Auction $auction)
    {
        $validated = $request->validate(['rejection_reason' => 'required|string|max:1000']);

        $auction->update([
            'status'            => Auction::STATUS_CANCELLED,
            'rejection_reason'  => $validated['rejection_reason'],
            'reviewed_by_admin_id' => Auth::guard('admin')->id(),
            'reviewed_at'       => now(),
        ]);

        return back()->with('success', 'Auction rejected.');
    }

    /**
     * Manual override — end an auction immediately regardless of timer,
     * for edge cases (fraud suspicion, seller withdrawal mid-auction, etc).
     * Like a normal timeout, this hands off to the seller's decision screen
     * rather than auto-finalizing anything.
     */
    public function forceEnd(Auction $auction)
    {
        $auction->update([
            'status' => Auction::STATUS_PENDING_SELLER_DECISION,
            'end_at' => now(),
        ]);

        return back()->with('success', 'Auction ended manually. Seller will now be asked to accept, negotiate, reject, or re-auction.');
    }

    public function cancel(Auction $auction)
    {
        $auction->update(['status' => Auction::STATUS_CANCELLED]);
        return back()->with('success', 'Auction cancelled.');
    }

    /**
     * Toggle one of the three manual verification-checklist sign-offs
     * (property tax, site visit, legal due diligence). Document-based
     * checks (KYC, ownership docs) are computed live and don't need this.
     */
    public function updateVerification(Request $request, Auction $auction)
    {
        $validated = $request->validate([
            'flag'  => 'required|in:property_tax,site,legal',
            'notes' => 'nullable|string|max:1000',
        ]);

        match ($validated['flag']) {
            'property_tax' => $auction->update(['property_tax_verified_at' => now()]),
            'site'         => $auction->update(['site_verified_at' => now()]),
            'legal'        => $auction->update([
                'legal_due_diligence_at'    => now(),
                'legal_due_diligence_notes' => $validated['notes'] ?? $auction->legal_due_diligence_notes,
            ]),
        };

        return back()->with('success', 'Verification checklist updated.');
    }

    public function markCompleted(Auction $auction)
    {
        abort_unless($auction->status === Auction::STATUS_WINNER_CONFIRMED, 404);
        $auction->update(['status' => Auction::STATUS_COMPLETED]);

        return back()->with('success', 'Auction marked as completed — sale finished offline (registration done).');
    }
}
