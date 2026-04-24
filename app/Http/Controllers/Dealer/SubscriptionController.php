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
        $payment->save();
        return redirect()->back()->with('success', 'Payment marked as completed!');
    }

    private function getPlanAmount($type, $plan)
    {
        // You can move this to config or DB as needed
        $plans = [
            'sale' => [
                'Starter' => 1499,
                'Value' => 3499,
                'Premium' => 6999,
                'Ultimate' => 11999,
            ],
            'rent' => [
                'Rent Starter' => 999,
                'Rent Value' => 2499,
                'Rent Premium' => 4999,
                'Rent Ultimate' => 8999,
            ],
            'pg' => [
                'PG Basic' => 799,
                'PG Value' => 1999,
                'PG Premium' => 3999,
                'PG Ultimate' => 6999,
            ],
        ];
        return $plans[$type][$plan] ?? 0;
    }
}
