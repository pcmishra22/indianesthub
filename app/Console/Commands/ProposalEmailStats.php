<?php

namespace App\Console\Commands;

use App\Models\EmailTracking;
use Illuminate\Console\Command;

class ProposalEmailStats extends Command
{
    protected $signature = 'emails:proposal-stats
                            {--opened   : Show only emails that were opened}
                            {--unopened : Show only emails not yet opened}
                            {--failed   : Show only emails that failed to send}
                            {--sent     : Show only successfully sent emails}';

    protected $description = 'Show delivery + open-tracking stats for sent proposal emails';

    public function handle(): void
    {
        $base = EmailTracking::where('email_type', 'proposal');

        $total   = (clone $base)->count();
        $sent    = (clone $base)->where('status', 'sent')->count();
        $failed  = (clone $base)->where('status', 'failed')->count();
        $pending = (clone $base)->where('status', 'pending')->count();
        $opened  = (clone $base)->where('status', 'sent')->where('open_count', '>', 0)->count();
        $rate    = $sent > 0 ? round(($opened / $sent) * 100, 1) : 0;

        $this->newLine();
        $this->line('  <fg=cyan;options=bold>Proposal Email Report — IndianestHub</>');
        $this->line('  ────────────────────────────────────────────');
        $this->line("  Total Records  : <fg=white;options=bold>{$total}</>");
        $this->line("  ✓ Delivered    : <fg=green;options=bold>{$sent}</>");
        $this->line("  ✗ Failed       : <fg=red;options=bold>{$failed}</>");
        $this->line("  ⏳ Pending     : <fg=yellow;options=bold>{$pending}</>");
        $this->line("  👁 Opened      : <fg=cyan;options=bold>{$opened}</> / {$sent} delivered  (<fg=cyan;options=bold>{$rate}%</>)");
        $this->line('  ────────────────────────────────────────────');
        $this->newLine();

        // Build filtered query for the table
        $query = (clone $base)->orderBy('sent_at', 'desc');

        if ($this->option('opened')) {
            $query->where('status', 'sent')->where('open_count', '>', 0);
        } elseif ($this->option('unopened')) {
            $query->where('status', 'sent')->where('open_count', 0);
        } elseif ($this->option('failed')) {
            $query->where('status', 'failed');
        } elseif ($this->option('sent')) {
            $query->where('status', 'sent');
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            $this->warn('No records match the selected filter.');
            return;
        }

        $rows = $records->map(function ($r) {
            $deliveryStatus = match ($r->status) {
                'sent'    => '<fg=green>✓ Sent</>',
                'failed'  => '<fg=red>✗ Failed</>',
                default   => '<fg=yellow>⏳ Pending</>',
            };

            $openStatus = $r->status === 'sent'
                ? ($r->open_count > 0
                    ? "<fg=green>✓ Opened ({$r->open_count}x) " . $r->first_opened_at?->format('d M, H:i') . '</>'
                    : '<fg=yellow>✗ Not opened</>')
                : '—';

            return [
                $r->recipient_name,
                $r->recipient_email,
                ucfirst($r->recipient_type),
                $r->sent_at?->format('d M Y, H:i') ?? '—',
                $deliveryStatus,
                $openStatus,
            ];
        })->toArray();

        $this->table(
            ['Name', 'Email', 'Type', 'Sent At', 'Delivery', 'Open Status'],
            $rows
        );

        $this->newLine();

        if ($this->option('failed') || (!$this->option('opened') && !$this->option('unopened') && !$this->option('sent'))) {
            $failedCount = (clone EmailTracking::where('email_type', 'proposal'))->where('status', 'failed')->count();
            if ($failedCount > 0) {
                $this->line("  <fg=yellow>Tip:</> To retry the {$failedCount} failed email(s), run:");
                $this->line('  <fg=cyan>php artisan emails:send-proposal --retry-failed --delay=3</>');
                $this->newLine();
            }
        }
    }
}
