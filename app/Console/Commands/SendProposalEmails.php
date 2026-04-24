<?php

namespace App\Console\Commands;

use App\Mail\ProposalEmail;
use App\Models\Builder;
use App\Models\Dealer;
use App\Models\EmailTracking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendProposalEmails extends Command
{
    protected $signature = 'emails:send-proposal
                            {--test          : Send test email only to pcmishra22@gmail.com}
                            {--type=         : Filter by type: dealers|builders (default: both)}
                            {--retry-failed  : Only retry emails that previously failed}
                            {--delay=2       : Seconds to wait between each email (default: 2)}
                            {--batch=0       : Max emails to send in this run, 0 = unlimited}';

    protected $description = 'Send plan proposal emails to all dealers and builders';

    private int $delay = 2;
    private int $batchLimit = 0;
    private int $batchSent  = 0;

    public function handle(): void
    {
        $this->delay      = max(0, (int) $this->option('delay'));
        $this->batchLimit = max(0, (int) $this->option('batch'));

        if ($this->option('test')) {
            $this->sendTestEmail();
            return;
        }

        if ($this->option('retry-failed')) {
            $this->retryFailed();
            return;
        }

        $type = $this->option('type');

        if (!$type || $type === 'dealers') {
            $this->sendToDealers();
        }

        if (!$type || $type === 'builders') {
            $this->sendToBuilders();
        }
    }

    // ── Test ─────────────────────────────────────────────────────────

    private function sendTestEmail(): void
    {
        $testEmail = 'pcmishra22@gmail.com';
        $this->info("Sending TEST proposal email to {$testEmail} ...");

        $token = $this->createTrackingRecord($testEmail, 'Prakash (Test)', 'dealer');

        try {
            Mail::to($testEmail)->send(new ProposalEmail('Prakash (Test)', 'dealer', $token));
            $this->markSent($token);
            $this->info("✓ Test email sent to {$testEmail}");
            $this->line('  Review it in your inbox, then run without --test to send to everyone.');
        } catch (\Throwable $e) {
            $this->markFailed($token, $e->getMessage());
            $this->error("✗ Failed: {$e->getMessage()}");
        }
    }

    // ── Dealers ───────────────────────────────────────────────────────

    private function sendToDealers(): void
    {
        $dealers = Dealer::whereNotNull('email')->get();

        if ($dealers->isEmpty()) {
            $this->warn('No dealers found in the database.');
            return;
        }

        $this->info("Processing {$dealers->count()} dealer(s)...");
        $sent = 0; $skipped = 0; $failed = 0;

        foreach ($dealers as $dealer) {
            if ($this->batchLimitReached()) break;

            // Skip if already successfully sent
            if ($this->alreadySent($dealer->email)) {
                $skipped++;
                continue;
            }

            $token = $this->createTrackingRecord($dealer->email, $dealer->first_name, 'dealer');

            try {
                Mail::to($dealer->email)
                    ->send(new ProposalEmail($dealer->first_name, 'dealer', $token));
                $this->markSent($token);
                $sent++;
                $this->batchSent++;
                $this->line("  ✓ Dealer: {$dealer->email}");
            } catch (\Throwable $e) {
                $this->markFailed($token, $e->getMessage());
                $failed++;
                $this->error("  ✗ Dealer {$dealer->email}: " . $this->shortError($e->getMessage()));
            }

            if ($this->delay > 0) {
                sleep($this->delay);
            }
        }

        $this->info("  Dealers done — ✓ {$sent} sent  — {$skipped} skipped  ✗ {$failed} failed.");
    }

    // ── Builders ──────────────────────────────────────────────────────

    private function sendToBuilders(): void
    {
        $builders = Builder::whereNotNull('email')->get();

        if ($builders->isEmpty()) {
            $this->warn('No builders found in the database.');
            return;
        }

        $this->info("Processing {$builders->count()} builder(s)...");
        $sent = 0; $skipped = 0; $failed = 0;

        foreach ($builders as $builder) {
            if ($this->batchLimitReached()) break;

            if ($this->alreadySent($builder->email)) {
                $skipped++;
                continue;
            }

            $token = $this->createTrackingRecord($builder->email, $builder->name, 'builder');

            try {
                Mail::to($builder->email)
                    ->send(new ProposalEmail($builder->name, 'builder', $token));
                $this->markSent($token);
                $sent++;
                $this->batchSent++;
                $this->line("  ✓ Builder: {$builder->email}");
            } catch (\Throwable $e) {
                $this->markFailed($token, $e->getMessage());
                $failed++;
                $this->error("  ✗ Builder {$builder->email}: " . $this->shortError($e->getMessage()));
            }

            if ($this->delay > 0) {
                sleep($this->delay);
            }
        }

        $this->info("  Builders done — ✓ {$sent} sent  — {$skipped} skipped  ✗ {$failed} failed.");
    }

    // ── Retry failed ──────────────────────────────────────────────────

    private function retryFailed(): void
    {
        $allFailed = EmailTracking::where('email_type', 'proposal')
            ->where('status', 'failed')
            ->get();

        if ($allFailed->isEmpty()) {
            $this->info('No failed emails found to retry.');
            return;
        }

        $total = $allFailed->count();
        $cap   = ($this->batchLimit > 0) ? min($this->batchLimit, $total) : $total;
        $this->info("Retrying {$cap} of {$total} failed email(s) with {$this->delay}s delay...");

        $sent = 0; $stillFailed = 0;

        foreach ($allFailed as $record) {
            if ($this->batchLimitReached()) break;

            // Generate fresh token
            $newToken = Str::random(48);
            $record->update([
                'token'          => $newToken,
                'status'         => 'pending',
                'failure_reason' => null,
                'sent_at'        => now(),
            ]);

            try {
                Mail::to($record->recipient_email)
                    ->send(new ProposalEmail($record->recipient_name, $record->recipient_type, $newToken));
                $this->markSent($newToken);
                $sent++;
                $this->batchSent++;
                $this->line("  ✓ Retried: {$record->recipient_email}");
            } catch (\Throwable $e) {
                $this->markFailed($newToken, $e->getMessage());
                $stillFailed++;
                $this->error("  ✗ Still failing {$record->recipient_email}: " . $this->shortError($e->getMessage()));
            }

            if ($this->delay > 0) {
                sleep($this->delay);
            }
        }

        $remaining = $total - $sent - $stillFailed;
        $this->info("Retry done — ✓ {$sent} recovered  ✗ {$stillFailed} still failed.");
        if ($remaining > 0) {
            $this->line("  {$remaining} more failed emails remain — run again to continue.");;
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────

    private function alreadySent(string $email): bool
    {
        return EmailTracking::where('email_type', 'proposal')
            ->where('recipient_email', $email)
            ->where('status', 'sent')
            ->exists();
    }

    private function createTrackingRecord(string $email, string $name, string $type): string
    {
        $token = Str::random(48);

        EmailTracking::create([
            'email_type'      => 'proposal',
            'recipient_email' => $email,
            'recipient_name'  => $name,
            'recipient_type'  => $type,
            'token'           => $token,
            'status'          => 'pending',
            'sent_at'         => now(),
        ]);

        return $token;
    }

    private function markSent(string $token): void
    {
        EmailTracking::where('token', $token)->update(['status' => 'sent']);
    }

    private function markFailed(string $token, string $reason): void
    {
        EmailTracking::where('token', $token)->update([
            'status'         => 'failed',
            'failure_reason' => mb_substr($reason, 0, 500),
        ]);
    }

    private function batchLimitReached(): bool
    {
        if ($this->batchLimit <= 0) {
            return false;
        }
        if ($this->batchSent >= $this->batchLimit) {
            $this->warn("  Batch limit of {$this->batchLimit} reached — stopping. Run again to continue.");
            return true;
        }
        return false;
    }

    private function shortError(string $message): string
    {
        if (str_contains($message, 'Ratelimit')) {
            return 'SMTP rate limit exceeded — run again later or increase --delay';
        }
        return mb_substr($message, 0, 120);
    }
}
