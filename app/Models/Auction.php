<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_SUBMITTED              = 'submitted';
    public const STATUS_UNDER_REVIEW           = 'under_review';
    public const STATUS_CHANGES_REQUESTED      = 'changes_requested';
    public const STATUS_APPROVED               = 'approved';
    public const STATUS_LIVE                   = 'live';
    public const STATUS_PENDING_SELLER_DECISION = 'pending_seller_decision';
    public const STATUS_WINNER_CONFIRMED       = 'winner_confirmed';
    public const STATUS_COMPLETED              = 'completed';
    public const STATUS_ENDED_UNSOLD           = 'ended_unsold';
    public const STATUS_CANCELLED              = 'cancelled';

    public const DECISION_ACCEPTED   = 'accepted';
    public const DECISION_NEGOTIATING = 'negotiating';
    public const DECISION_REJECTED   = 'rejected';
    public const DECISION_REAUCTION  = 'reauction_requested';

    protected $fillable = [
        'property_id', 'seller_user_id', 'seller_dealer_id', 'status',
        'reserve_price', 'starting_bid', 'bid_increment', 'emd_amount',
        'current_highest_bid', 'current_highest_bidder_id',
        'scheduled_start_at', 'start_at', 'end_at', 'original_end_at',
        'extension_count', 'max_extensions',
        'reviewed_by_admin_id', 'reviewed_at', 'admin_notes', 'rejection_reason',
        'sale_reason', 'sale_reason_public', 'duration_days_requested',
        'property_tax_verified_at', 'site_verified_at',
        'legal_due_diligence_at', 'legal_due_diligence_notes',
        'seller_decision', 'seller_decision_at',
    ];

    protected $casts = [
        'reserve_price'        => 'decimal:2',
        'starting_bid'         => 'decimal:2',
        'bid_increment'        => 'decimal:2',
        'emd_amount'           => 'decimal:2',
        'current_highest_bid'  => 'decimal:2',
        'scheduled_start_at'   => 'datetime',
        'start_at'             => 'datetime',
        'end_at'               => 'datetime',
        'original_end_at'      => 'datetime',
        'reviewed_at'          => 'datetime',
        'sale_reason_public'   => 'boolean',
        'property_tax_verified_at' => 'datetime',
        'site_verified_at'         => 'datetime',
        'legal_due_diligence_at'   => 'datetime',
        'seller_decision_at'       => 'datetime',
    ];

    // ── Relations ─────────────────────────────────────────

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function sellerUser()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    public function sellerDealer()
    {
        return $this->belongsTo(Dealer::class, 'seller_dealer_id');
    }

    public function documents()
    {
        return $this->hasMany(AuctionDocument::class);
    }

    public function bids()
    {
        return $this->hasMany(AuctionBid::class)->orderByDesc('amount');
    }

    public function currentHighestBidder()
    {
        return $this->belongsTo(User::class, 'current_highest_bidder_id');
    }

    public function deposits()
    {
        return $this->hasMany(Payment::class, 'auction_id')->where('payment_type', 'auction_deposit');
    }

    // ── Helpers ───────────────────────────────────────────

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE
            && $this->start_at && $this->start_at->isPast()
            && $this->end_at && $this->end_at->isFuture();
    }

    public function hasEnded(): bool
    {
        return $this->end_at && $this->end_at->isPast();
    }

    public function reserveMet(): bool
    {
        return $this->current_highest_bid !== null
            && (float) $this->current_highest_bid >= (float) $this->reserve_price;
    }

    public function minimumNextBid(): float
    {
        if ($this->current_highest_bid === null) {
            return (float) $this->starting_bid;
        }
        return (float) $this->current_highest_bid + (float) $this->bid_increment;
    }

    public function documentsAllApproved(): bool
    {
        $required = ['sale_deed', 'ownership_proof', 'identity_proof'];
        $approvedTypes = $this->documents()->where('status', 'approved')->pluck('document_type')->all();
        foreach ($required as $type) {
            if (! in_array($type, $approvedTypes, true)) {
                return false;
            }
        }
        return true;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_SUBMITTED               => 'Documents Pending',
            self::STATUS_UNDER_REVIEW            => 'Under Review',
            self::STATUS_CHANGES_REQUESTED       => 'Changes Requested',
            self::STATUS_APPROVED                => 'Approved · Scheduled',
            self::STATUS_LIVE                    => 'Live Now',
            self::STATUS_PENDING_SELLER_DECISION => 'Ended · Awaiting Seller',
            self::STATUS_WINNER_CONFIRMED        => 'Winner Confirmed',
            self::STATUS_COMPLETED               => 'Completed',
            self::STATUS_ENDED_UNSOLD            => 'Unsold',
            self::STATUS_CANCELLED               => 'Cancelled',
            default                              => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Whether the reserve price should ever be shown as a number anywhere
     * public-facing. It shouldn't — see reserveStatusLabel().
     */
    public function reserveStatusLabel(): string
    {
        return $this->reserveMet() ? 'Reserve Met' : 'Reserve Not Met';
    }

    /**
     * 5-level verification checklist shown on the public auction page,
     * mirroring the "Owner KYC / Ownership docs / Property tax / Site
     * verification / Legal report" mockup exactly. Each level is either a
     * live computed check (KYC, documents) or an explicit manual admin
     * sign-off (property tax, site visit, legal due diligence) — nothing is
     * inferred or auto-marked "verified" just because a file was uploaded.
     */
    public function verificationChecklist(): array
    {
        $ownershipApproved = $this->documents()
            ->where('document_type', 'ownership_proof')
            ->where('status', 'approved')
            ->exists();

        return [
            ['label' => 'Owner KYC',                  'done' => (bool) ($this->sellerUser?->hasVerifiedKyc())],
            ['label' => 'Ownership Documents Reviewed', 'done' => $ownershipApproved],
            ['label' => 'Property Tax Checked',         'done' => $this->property_tax_verified_at !== null],
            ['label' => 'Site Verification',            'done' => $this->site_verified_at !== null],
            ['label' => 'Legal Report Available',       'done' => $this->legal_due_diligence_at !== null],
        ];
    }

    public function verificationLevel(): int
    {
        return collect($this->verificationChecklist())->filter(fn ($c) => $c['done'])->count();
    }

    /**
     * The EMD required to bid. Seller/admin sets this explicitly on a
     * per-auction basis (per the "₹50L property → ₹1L EMD" example) — the
     * old flat 1%-of-reserve formula is now only a fallback for auctions
     * created before this field existed.
     */
    public function emdAmount(): float
    {
        if ($this->emd_amount !== null) {
            return (float) $this->emd_amount;
        }
        return max(5000, round((float) $this->reserve_price * 0.01, -2));
    }

    public function sellerCanDecide(): bool
    {
        return $this->status === self::STATUS_PENDING_SELLER_DECISION;
    }

    /**
     * Reject/Re-auction are only offered when the reserve wasn't met —
     * if it was met, the seller can only Accept or attempt to Negotiate
     * a higher price with the highest bidder (they can't just walk away
     * from a reserve that was cleared).
     */
    public function availableSellerDecisions(): array
    {
        if (! $this->sellerCanDecide()) {
            return [];
        }
        if ($this->reserveMet()) {
            return [self::DECISION_ACCEPTED, self::DECISION_NEGOTIATING];
        }
        return [self::DECISION_NEGOTIATING, self::DECISION_REJECTED, self::DECISION_REAUCTION];
    }
}
