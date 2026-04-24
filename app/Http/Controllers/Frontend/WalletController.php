<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display the wallet page.
     */
    public function index()
    {
        // For now, just return a view with empty wallet data
        $wallet = null;
        return view('frontend.wallet.index', compact('wallet'));
    }
}
