<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaxrateRequest;
use App\Models\Country;
use App\Models\TaxRate;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTaxrateRequest;

class TaxrateController extends Controller
{
    public function index(Request $request)
    {
        $taxrates = TaxRate::orderBy('id', 'DESC');

        $isFiltered = false;

        // Set filter array for name
        $filters = [
            'name' => $request->name,
        ];

        // Filter by status
        $status = $request->status;
        if ($status) {
            $taxrates->where('status', $status);
            $isFiltered = true;
        }

        // Filter by name
        foreach ($filters as $column => $value) {
            if ($value) {
                $taxrates->where($column, 'like', '%' . $value . '%');
                $isFiltered = true;
            }
        }

        $taxrates = $taxrates->paginate(config('app.pagination'))->withQueryString();

        return view('admin.taxrate.index', [
            'pageTitle' => __('Tax Rates'),
            'taxrates' => $taxrates,
            'isFiltered' => $isFiltered,
            'filters' => $filters,
            'status' => $status,
        ]);

    }

}