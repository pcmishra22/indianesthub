<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PriceTrendsController extends Controller
{
    public function index()
    {
        // Dummy data for price trends
        $trends = [
            ['month' => 'Jan', 'price' => 700000],
            ['month' => 'Feb', 'price' => 710000],
            ['month' => 'Mar', 'price' => 720000],
            ['month' => 'Apr', 'price' => 730000],
        ];
        return view('frontend.price-trends', compact('trends'));
    }
}
