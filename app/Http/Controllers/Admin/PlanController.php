<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = Plan::orderBy('id', 'DESC');

        $isFiltered = false;

        // Setting array for filter
        $filters = [
            'name' => $request->name,
            'description' => $request->description,
            'currency' => $request->currency,
            'status' => $request->status,
        ];

        // Filter name, description, currency and status
        foreach ($filters as $column => $value) {
            if ($value) {
                $plans->where($column, 'like', '%' . $value . '%');
                $isFiltered = true;
            }
        }

        $plans = $plans->paginate(config('app.pagination'))->withQueryString();

        // Get all currencies from database
        $currencies = Currency::get();

        return view('admin.plan.index', [
            'pageTitle' => __('Plans'),
            'currencies' => $currencies,
            'plans' => $plans,
            'filters' => $filters,
            'isFiltered' => $isFiltered,
        ]);
    }
}