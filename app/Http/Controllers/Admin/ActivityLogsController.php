<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsController extends Controller
{
    public function index(Request $request)
    {
        $activityLogs = Activity::with('causer', 'subject')->orderByDesc('id');

        $isFiltered = false;

        $filters = [
            'subject_type' => $request->module,
            'ip' => $request->ip,
            'event' => $request->event,
        ];

        $username = $request->username;
        if ($username) {
            $activityLogs->whereHas('causer', function ($query) use ($username) {
                $query->where('username', $username);
            });

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

            $activityLogs->whereBetween('created_at', [$startDateWithTime, $endDateWithTime]);
            $isFiltered = true;
        }

        foreach ($filters as $column => $value) {
            if ($value) {
                $activityLogs->where($column, 'like', '%' . $value . '%');
                $isFiltered = true;
            }
        }

        $activityLogs = $activityLogs->paginate(config('app.pagination'))->withQueryString();

        return view('admin.activity-log.index', [
            'pageTitle' => __('Activity Logs'),
            'activityLogs' => $activityLogs,
            'isFiltered' => $isFiltered,
            'filters' => $filters,
            'username' => $username,
            'dateRange' => $dateRange,

        ]);
    }

    public function exportActivityLog(Request $request)
    {
        $query = Activity::query();

        if ($request->has('module')) {
            $query->where('subject_type', 'like', '%' . $request->module . '%');
        }

        if ($request->has('username')) {
            $username = $request->username;
            $query->whereHas('causer', function ($usernameQuery) use ($username) {
                $usernameQuery->where('username', $username);
            });
        }

        if ($request->has('event')) {
            $query->where('event', 'like', '%' . $request->event . '%');
        }

        if ($request->has('ip')) {
            $query->where('ip', 'like', '%' . $request->title . '%');
        }

        if ($request->has('created_date')) {
            $explodedDateRange = explode('-', $request->created_date);
            $startDate = trim($explodedDateRange[0]);
            $startDateWithTime = Carbon::createFromFormat('Y/m/d', $startDate)->format('Y-m-d') . " 00:00:00";

            $endDate = trim($explodedDateRange[1]);
            $endDateWithTime = Carbon::createFromFormat('Y/m/d', $endDate)->format('Y-m-d') . " 23:59:59";

            $query->whereBetween('created_at', [$startDateWithTime, $endDateWithTime]);
        }

        $activityLogs = $query->get();
        $arrayToExport = [];

        foreach ($activityLogs as $activityLog) {
            $arrayToExport[] = [
                'Log name' => $activityLog->log_name,
                'event' => $activityLog->event,
                'Causer' => $activityLog->causer ? $activityLog->causer->username : '-',
                'Created at' => $activityLog->created_at->toDateTimestring(),
            ];
        }

        if (empty($arrayToExport)) {
            return back()->with('error', 'No data available to export.');
        }

        return exportCSV('activity-logs.csv', $arrayToExport);
    }
}