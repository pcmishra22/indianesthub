<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $type = $request->query('type');
        $plan = $request->query('plan');
        $amount = $this->getPlanAmount($type, $plan);

        // Create a payment record (status: pending)
        $dealerId = Auth::guard('dealer')->id();
        $payment = Payment::create([
            'dealer_id' => $dealerId,
            'plan_type' => $type,
            'plan_name' => $plan,
            'amount' => $amount,
            'status' => 'pending',
            'payment_method' => 'upi',
        ]);

        // Use UPI_ID from .env
        $upi_id = env('UPI_ID', 'your-upi-id@bank');
        $upi_url = "upi://pay?pa=$upi_id&pn=PropertyDealer&am=$amount&tn=Subscription%20Payment";

        return view('dealer.payment', [
            'type' => $type,
            'plan' => $plan,
            'amount' => $amount,
            'upi_url' => $upi_url,
            'payment' => $payment,
        ]);
    }

    public function markPaid(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        // Optionally, check if the payment belongs to the current dealer
        $payment->status = 'completed';

        if ($payment->payment_type === 'property_listing') {
            $days = $payment->listing_duration_days ?: 30;
            $payment->listing_start_date = $payment->listing_start_date ?: now();
            $payment->listing_end_date = now()->addDays($days);
        }

        $payment->save();
        return redirect()->back()->with('success', 'Payment marked as completed!');
    }

    private function getPlanAmount($type, $plan)
    {
        // Fixed pricing: ₹100/month for rental, ₹500/month for sale
        $plans = [
            'sale' => [
                'Starter' => 500,
                'Value' => 500,
                'Premium' => 500,
                'Ultimate' => 500,
            ],
            'rent' => [
                'Rent Starter' => 100,
                'Rent Value' => 100,
                'Rent Premium' => 100,
                'Rent Ultimate' => 100,
            ],
            'pg' => [
                'PG Basic' => 100,
                'PG Value' => 100,
                'PG Premium' => 100,
                'PG Ultimate' => 100,
            ],
        ];
        return $plans[$type][$plan] ?? 0;
    }
}
