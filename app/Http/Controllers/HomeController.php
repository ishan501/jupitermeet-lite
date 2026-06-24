<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Plan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        if (!file_exists(storage_path('installed'))) {
            return redirect('/install');
        }
        
        $plans = Plan::where('status', 1)->get();
        $page = Page::where('slug', 'home')->select('content')->first();

        return view('home', [
            'plans' => $plans,
            'page' => $page
        ]);
    }
}
