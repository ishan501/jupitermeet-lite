<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPaymentMode;
use App\Models\Plan;

class PricingController extends Controller
{
    public function __construct()
    {
        $this->middleware(CheckPaymentMode::class);
    }

    public function index()
    {
        $plans = Plan::where('status', 1)->get();

        return view('pricing', ['plans' => $plans, 'pageTitle' => __('Pricing')]);
    }

}