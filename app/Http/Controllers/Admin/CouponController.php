<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::orderBy('id', 'DESC');
        $isFiltered = false;

        // Set filter array by code, name, type 
        $filters = [
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
        ];

        // Filter by status
        $status = $request->status;
        if ($status) {
            $coupons->where('status', $status);
            $isFiltered = true;
        }

        // Filter by code , name and type
        foreach ($filters as $column => $value) {
            if ($value !== null) { // This allows 0 to be included
                $coupons->where($column, 'like', '%' . $value . '%');
                $isFiltered = true;
            }
        }

        $coupons = $coupons->paginate(config('app.pagination'))->withQueryString();

        return view('admin.coupon.index', [
            'pageTitle' => __('Coupons'),
            'coupons' => $coupons,
            'filters' => $filters,
            'status' => $status,
            'isFiltered' => $isFiltered,
        ]);
    }
}