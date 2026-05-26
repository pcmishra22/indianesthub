<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DealerBulkEmail;
use App\Models\BulkEmail;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DealerBulkEmailController extends Controller
{
    public function index()
    {
        $emails = BulkEmail::latest()->paginate(10);
        return view('backend.dealers.bulk-email.index', compact('emails'));
    }

    public function create()
    {
        return view('backend.dealers.bulk-email.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'body'    => ['required', 'string', 'max:5000'],
        ]);

        BulkEmail::create($validated);

        return redirect()
            ->route('admin.dealers.bulk-email.index')
            ->with('success', 'Bulk email draft saved successfully.');
    }

    public function edit($id)
    {
        $email = BulkEmail::findOrFail($id);
        return view('backend.dealers.bulk-email.edit', compact('email'));
    }

    public function update(Request $request, $id)
    {
        $email = BulkEmail::findOrFail($id);

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'body'    => ['required', 'string', 'max:5000'],
        ]);

        $email->update($validated);

        return redirect()
            ->route('admin.dealers.bulk-email.index')
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

        Dealer::query()
            ->where('status', 'active')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->chunk(200, function ($dealers) use ($subject, $body) {
                foreach ($dealers as $dealer) {
                    Mail::to($dealer->email)->queue(
                        new DealerBulkEmail($dealer, $subject, $body)
                    );
                }
            });

        return redirect()
            ->route('admin.dealers.bulk-email.index')
            ->with('success', 'Emails queued successfully for all active dealers.');
    }
}
