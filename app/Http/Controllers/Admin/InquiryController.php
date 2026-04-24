<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function index() {
        $inquiries = Inquiry::all();
        return view('backend.inquiries.index', compact('inquiries'));
    }
    public function show($id) { return view('backend.inquiries.show', compact('id')); }
    public function destroy($id) { /* delete logic */ return back(); }
}
