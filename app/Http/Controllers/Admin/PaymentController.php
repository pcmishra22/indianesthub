<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index() {
        $payments = Payment::with(['dealer', 'user', 'auction.property'])->latest()->paginate(20);
        return view('backend.payments.index', compact('payments'));
    }

    public function approve($id) {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'completed') {
            return back()->with('info', 'This payment was already approved.');
        }

        $payment->status = 'completed';

        // Property listing payments: (re)activate the listing window so the
        // dealer's "is_paid" check (payments.status=completed AND listing_end_date >= now)
        // picks it up immediately.
        if ($payment->payment_type === 'property_listing') {
            $days = $payment->listing_duration_days ?: 30;
            $payment->listing_start_date = $payment->listing_start_date ?: now();
            $payment->listing_end_date = now()->addDays($days);
        }

        $payment->save();

        // Auction deposits don't need a listing window — the moment this is
        // marked completed, User::hasVerifiedDepositFor() unlocks bidding on
        // that specific auction for that user.
        if ($payment->payment_type === 'auction_deposit') {
            return back()->with('success', 'Auction deposit verified — bidder can now place bids.');
        }

        return back()->with('success', 'Payment approved successfully.');
    }

    public function reject($id) {
        $payment = Payment::findOrFail($id);
        $payment->status = 'failed';
        $payment->save();

        return back()->with('success', 'Payment marked as failed/rejected.');
    }
}
