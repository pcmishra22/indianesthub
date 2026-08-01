<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BulkAudienceEmail;
use App\Models\BulkEmail;
use App\Models\Builder;
use App\Models\Dealer;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserBulkEmailController extends Controller
{
    /**
     * Audience key => [display label, Eloquent model, "name" attribute/accessor,
     * status column value that means "in good standing"]. Only recipients
     * matching that status receive mail — e.g. blocked users/dealers/builders
     * and not-yet-approved service providers are always excluded, matching
     * what the previous per-audience-specific controllers already enforced.
     */
    public static function audiences(): array
    {
        return [
            'users'             => ['label' => 'All Users (Customers)', 'model' => User::class, 'name_attr' => 'name', 'status' => 'active'],
            'dealers'           => ['label' => 'All Dealers', 'model' => Dealer::class, 'name_attr' => 'full_name', 'status' => 'active'],
            'builders'          => ['label' => 'All Builders', 'model' => Builder::class, 'name_attr' => 'name', 'status' => 'active'],
            'service_providers' => ['label' => 'All Service Providers', 'model' => ServiceProvider::class, 'name_attr' => 'display_name', 'status' => 'approved'],
            'all'               => ['label' => 'Everyone (Users + Dealers + Builders + Service Providers)', 'model' => null, 'name_attr' => null, 'status' => null],
        ];
    }

    protected function recipientCount(string $audience): int
    {
        $audiences = static::audiences();

        if ($audience === 'all') {
            return collect($audiences)
                ->except('all')
                ->sum(fn ($a) => $a['model']::whereNotNull('email')->where('email', '!=', '')
                    ->when($a['status'], fn ($q) => $q->where('status', $a['status']))
                    ->count());
        }

        $target = $audiences[$audience] ?? null;
        if (!$target || !$target['model']) {
            return 0;
        }

        return $target['model']::whereNotNull('email')->where('email', '!=', '')
            ->when($target['status'], fn ($q) => $q->where('status', $target['status']))
            ->count();
    }

    public function index()
    {
        $emails = BulkEmail::latest()->paginate(10);
        $audiences = static::audiences();
        return view('backend.users.bulk-email.index', compact('emails', 'audiences'));
    }

    public function create()
    {
        $audiences = static::audiences();
        return view('backend.users.bulk-email.create', compact('audiences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject'  => ['required', 'string', 'max:150'],
            'body'     => ['required', 'string', 'max:5000'],
            'audience' => ['required', 'in:' . implode(',', array_keys(static::audiences()))],
        ]);

        BulkEmail::create($validated);

        return redirect()
            ->route('admin.users.bulk-email.index')
            ->with('success', 'Bulk email draft saved successfully.');
    }

    public function edit($id)
    {
        $email = BulkEmail::findOrFail($id);
        $audiences = static::audiences();
        return view('backend.users.bulk-email.edit', compact('email', 'audiences'));
    }

    public function update(Request $request, $id)
    {
        $email = BulkEmail::findOrFail($id);

        $validated = $request->validate([
            'subject'  => ['required', 'string', 'max:150'],
            'body'     => ['required', 'string', 'max:5000'],
            'audience' => ['required', 'in:' . implode(',', array_keys(static::audiences()))],
        ]);

        $email->update($validated);

        return redirect()
            ->route('admin.users.bulk-email.index')
            ->with('success', 'Bulk email updated successfully.');
    }

    public function destroy($id)
    {
        BulkEmail::findOrFail($id)->delete();
        return back()->with('success', 'Email draft deleted.');
    }

    public function queue($id)
    {
        $email = BulkEmail::findOrFail($id);
        $email->update(['status' => 'queued']);

        $subject = $email->subject;
        $body = $email->body;
        $audiences = static::audiences();

        $targets = $email->audience === 'all'
            ? collect($audiences)->except('all')->values()
            : collect([$audiences[$email->audience] ?? $audiences['users']]);

        $totalQueued = 0;

        foreach ($targets as $target) {
            $model = $target['model'];
            $nameAttr = $target['name_attr'];
            $statusValue = $target['status'];

            $model::query()
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->when($statusValue, fn ($q) => $q->where('status', $statusValue))
                ->chunk(200, function ($recipients) use ($subject, $body, $nameAttr, &$totalQueued) {
                    foreach ($recipients as $recipient) {
                        $name = $recipient->{$nameAttr} ?: 'there';
                        Mail::to($recipient->email)->queue(
                            new BulkAudienceEmail($name, $subject, $body)
                        );
                        $totalQueued++;
                    }
                });
        }

        return redirect()
            ->route('admin.users.bulk-email.index')
            ->with('success', "Email queued successfully for {$totalQueued} recipient(s).");
    }
}
