<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Mail\ContactNotification;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        return view('frontend.contact');
    }

    /**
     * Store a new contact message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        // Send email notifications to both admin email addresses
        $emails = ['admin@indianesthub.com', 'pcmishra22@gmail.com'];
        
        foreach ($emails as $email) {
            Mail::to($email)->send(new ContactNotification($contact));
        }

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
