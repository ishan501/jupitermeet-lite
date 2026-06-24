<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::orderBy('id', 'DESC');

        $isFiltered = false;

        // Set filter array for coupon, interval, gateway and payment_id
        $filters = [
            'coupon' => $request->coupon,
            'interval' => $request->type,
            'gateway' => $request->payment_gateway,
            'payment_id' => $request->transaction_id,
        ];

        // Filter payments by username
        $username = $request->username;
        if ($username) {
            $payments->whereHas('user', function ($query) use ($username) {
                $query->where('username', 'LIKE', '%' . $username . '%');
            });
            $isFiltered = true;
        }

        // Filter payments by plan
        $plan = $request->plan;
        if ($plan) {
            $payments->whereHas('plan', function ($query) use ($plan) {
                $query->where('name', 'LIKE', '%' . $plan . '%');
            });
            $isFiltered = true;
        }

        // Filter by coupon, interval, gateway and payment_id
        foreach ($filters as $column => $value) {
            if ($value) {
                $payments->where($column, 'like', '%' . $value . '%');
                $isFiltered = true;
            }
        }

        $payments = $payments->paginate(config('app.pagination'))->withQueryString();

        return view('admin.transaction.index', [
            'pageTitle' => __('Transactions'),
            'payments' => $payments,
            'username' => $username,
            'plan' => $plan,
            'filters' => $filters,
            'isFiltered' => $isFiltered,
        ]);
    }
}
