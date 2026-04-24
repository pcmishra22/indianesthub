<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index() {
        $payments = Payment::all();
        return view('backend.payments.index', compact('payments'));
    }
    public function approve($id) { /* approve logic */ return back(); }
}
