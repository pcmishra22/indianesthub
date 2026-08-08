<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id', 'user_id', 'amount', 'is_winning', 'is_auto_bid', 'ip_address',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'is_winning'  => 'boolean',
        'is_auto_bid' => 'boolean',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function bidder()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Stable, anonymous per-bidder-per-auction label for public display
     * (e.g. "Bidder #A821") — same bidder always gets the same label within
     * one auction (so the bid history reads coherently), but a different
     * label in every other auction, so bidding patterns can't be tracked
     * across listings from the public side. Real identities stay visible
     * to admins via the bidder() relation.
     */
    public function anonymousLabel(): string
    {
        $hash = substr(md5($this->auction_id . '-bidder-' . $this->user_id), 0, 6);
        $letter = strtoupper($hash[0]);
        $digits = (hexdec(substr($hash, 1, 3)) % 900) + 100;
        return "Bidder #{$letter}{$digits}";
    }
}
