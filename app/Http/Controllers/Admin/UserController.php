<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Jobs\SendUserCreationEmail;
use App\Models\Country;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'end-user')
            ->with('plan')
            ->orderByDesc('id');

        $isFiltered = false;

        // Set filter array for username and email
        $filters = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
        ];

        // Filter user status
        $status = $request->status;
        if ($status) {
            $users->where('status', $status);
            $isFiltered = true;
        }

        // Filter by daterange
        $dateRange = $request->created_date;
        $startDate = null;
        $endDate = null;

        if ($dateRange) {
            $explodedDateRange = explode('-', $dateRange);
            $startDate = trim($explodedDateRange[0]);
            $startDateWithTime = Carbon::createFromFormat('Y/m/d', $startDate)->format('Y-m-d') . " 00:00:00";

            $endDate = trim($explodedDateRange[1]);
            $endDateWithTime = Carbon::createFromFormat('Y/m/d', $endDate)->format('Y-m-d') . " 23:59:59";

            $users->whereBetween('created_at', [$startDateWithTime, $endDateWithTime]);
            $isFiltered = true;
        }

        // Filter username and email
        foreach ($filters as $column => $value) {
            if ($value) {
                $users->where($column, 'like', '%' . $value . '%');
                $isFiltered = true;
            }
        }

        $users = $users->paginate(config('app.pagination'))->withQueryString();

        // Get all plans
        $plans = Plan::where('status', 1)->get();

        return view('admin.user.index', [
            'pageTitle' => __('Users'),
            'users' => $users,
            'plans' => $plans,
            'filters' => $filters,
            'dateRange' => $dateRange,
            'status' => $status,
            'isFiltered' => $isFiltered
        ]);
    }

    // Show create user form
    public function create(Request $request)
    {
        $countries = Country::all();
        return view('admin.user.create', ['pageTitle' => __('Create User'), 'countries' => $countries]);
    }

    // Store the details of new user
    public function store(CreateUserRequest $request)
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->country_id = $request->country_id;
        $user->save();

        $details = [
            'username' => $user->username,
            'email' => $user->email,
            'password' => $request->password,
        ];

        SendUserCreationEmail::dispatch($details);

        if (getSetting('VERIFY_USERS') == 'enabled') {
            $user->sendEmailVerificationNotification();
        }

        return redirect()->route('admin.user')->with('message', __('User created Successfully.'));
    }

    // Assign plan to user through dropdown
    public function assignPlan(Request $request)
    {
        $user = User::find($request->user_id);
        $user->plan_id = $request->plan_id;
        $user->save();

        return response()->json(['success' => true, 'error' => '', 'data' => [], 'message' => __('Plan Assigned Successfullly.')]);
    }

    // Update user status via toggle
    public function updateStatus(Request $request)
    {
        $user = User::find($request->userId);
        $user->status = $request->userStatus;
        $user->save();

        return response()->json(['success' => true, 'error' => '', 'data' => [], 'message' => __('Status Updated Successfully')]);
    }

    // Soft delete user
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user')->with('message', __('User Deleted Successfully.'));

    }

    public function exportUser(Request $request)
    {
        $query = User::with('plan')->select('first_name', 'last_name', 'username', 'email', 'status', 'plan_id', 'created_at')->where('role', 'end-user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('username')) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('created_date')) {
            $explodedDateRange = explode('-', $request->created_date);
            $startDate = trim($explodedDateRange[0]);
            $startDateWithTime = Carbon::createFromFormat('Y/m/d', $startDate)->format('Y-m-d') . " 00:00:00";

            $endDate = trim($explodedDateRange[1]);
            $endDateWithTime = Carbon::createFromFormat('Y/m/d', $endDate)->format('Y-m-d') . " 23:59:59";

            $query->whereBetween('created_at', [$startDateWithTime, $endDateWithTime]);
        }

        $users = $query->get();
        $arrayToExport = [];

        foreach ($users as $user) {
            $arrayToExport[] = [
                'First Name' => $user->first_name ?? '-',
                'Last Name' => $user->last_name ?? '-',
                'Username' => $user->username,
                'Email' => $user->email,
                'Status' => $user->status,
                'Created_at' => $user->created_at,
                'Plan name' => $user->plan->name
            ];
        }

        if (empty($arrayToExport)) {
            return back()->with('error', 'No data available to export.');
        }

        return exportCSV('users.csv', $arrayToExport);
    }
}
