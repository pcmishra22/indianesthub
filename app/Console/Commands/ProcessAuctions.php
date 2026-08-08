<?php

namespace App\Console\Commands;

use App\Models\Auction;
use Illuminate\Console\Command;

class ProcessAuctions extends Command
{
    protected $signature = 'auctions:process';

    protected $description = 'Activate scheduled auctions whose start time has arrived, and close out auctions whose end time has passed (auto-extending if the reserve price was not met).';

    public function handle(): int
    {
        $this->activateScheduledAuctions();
        $this->closeExpiredAuctions();

        return self::SUCCESS;
    }

    private function activateScheduledAuctions(): void
    {
        $toActivate = Auction::where('status', Auction::STATUS_APPROVED)
            ->whereNotNull('start_at')
            ->where('start_at', '<=', now())
            ->get();

        foreach ($toActivate as $auction) {
            $auction->update(['status' => Auction::STATUS_LIVE]);
            $this->info("Auction #{$auction->id} is now live.");
        }
    }

    private function closeExpiredAuctions(): void
    {
        $expired = Auction::where('status', Auction::STATUS_LIVE)
            ->whereNotNull('end_at')
            ->where('end_at', '<=', now())
            ->get();

        foreach ($expired as $auction) {
            // The platform no longer auto-decides anything at this point —
            // per spec, the seller always gets to Accept / Negotiate /
            // Reject / Re-auction once bidding closes, whether or not the
            // reserve was cleared.
            $auction->update(['status' => Auction::STATUS_PENDING_SELLER_DECISION]);

            if ($auction->reserveMet()) {
                $this->info("Auction #{$auction->id} ended, reserve met at ₹{$auction->current_highest_bid} — awaiting seller decision.");
            } else {
                $this->info("Auction #{$auction->id} ended, reserve not met — awaiting seller decision (negotiate/reject/re-auction).");
            }
            // TODO: notify seller that a decision is needed.
        }
    }
}
