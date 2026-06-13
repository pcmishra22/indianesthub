<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserBulkEmail;
use App\Models\BulkEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserBulkEmailController extends Controller
{
    public function index()
    {
        $emails = BulkEmail::latest()->paginate(10);
        return view('backend.users.bulk-email.index', compact('emails'));
    }

    public function create()
    {
        return view('backend.users.bulk-email.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'body'    => ['required', 'string', 'max:5000'],
        ]);

        BulkEmail::create($validated);

        return redirect()
            ->route('admin.users.bulk-email.index')
            ->with('success', 'Bulk email draft saved successfully.');
    }

    public function edit($id)
    {
        $email = BulkEmail::findOrFail($id);
        return view('backend.users.bulk-email.edit', compact('email'));
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

        // We target the User model (Customers)
        // Assuming users have an 'active' status or similar filter
        User::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->chunk(200, function ($users) use ($subject, $body) {
                foreach ($users as $user) {
                    Mail::to($user->email)->queue(
                        new UserBulkEmail($user, $subject, $body)
                    );
                }
            });

        return redirect()
            ->route('admin.users.bulk-email.index')
            ->with('success', 'Emails queued successfully for all customers.');
    }
}