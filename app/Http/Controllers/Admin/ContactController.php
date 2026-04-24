<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index() {
        $contacts = Contact::all();
        return view('backend.contacts.index', compact('contacts'));
    }
    public function show($id) { return view('backend.contacts.show', compact('id')); }
    public function destroy($id) { /* delete logic */ return back(); }
}
