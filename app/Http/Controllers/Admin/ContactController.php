<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index() {
        $contacts = Contact::latest()->paginate(20);
        return view('backend.contacts.index', compact('contacts'));
    }

    public function show($id) {
        $contact = Contact::findOrFail($id);

        // Viewing a contact message marks it as read.
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }

        return view('backend.contacts.show', compact('contact'));
    }

    public function markRead($id) {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => 'read']);

        return back()->with('success', 'Marked as read.');
    }

    public function destroy($id) {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return back()->with('success', 'Contact message deleted.');
    }
}
