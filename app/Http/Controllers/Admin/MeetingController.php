<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $meetings = Meeting::with('user')
            ->whereDoesntHave('user', function ($query) {
                $query->whereColumn('users.username', 'meetings.meeting_id');
            })
            ->orderByDesc('id');

        $isFiltered = false;

        // Setting array for filter
        $filters = [
            'title' => $request->title,
            'description' => $request->description,
            'meeting_id' => $request->meeting_id,
        ];

        // Filter status
        $status = $request->status;
        if ($status) {
            $meetings->where('status', $status);
            $isFiltered = true;
        }

        // Filter by username
        $username = $request->username;
        if ($username) {
            $meetings->whereHas('user', function ($query) use ($username) {
                $query->where('username', 'LIKE', '%' . $username . '%');
            });
            $isFiltered = true;
        }

        // Filter by date range
        $dateRange = $request->created_date;
        $startDate = null;
        $endDate = null;


        if ($dateRange) {
            $explodedDateRange = explode('-', $dateRange);
            $startDate = trim($explodedDateRange[0]);
            $startDateWithTime = Carbon::createFromFormat('Y/m/d', $startDate)->format('Y-m-d') . " 00:00:00";

            $endDate = trim($explodedDateRange[1]);
            $endDateWithTime = Carbon::createFromFormat('Y/m/d', $endDate)->format('Y-m-d') . " 23:59:59";

            $meetings->whereBetween('created_at', [$startDateWithTime, $endDateWithTime]);
            $isFiltered = true;
        }

        // Filter title, description and meeting ID
        foreach ($filters as $column => $value) {
            if ($value) {
                $meetings->where($column, 'like', '%' . $value . '%');
                $isFiltered = true;
            }
        }

        $meetings = $meetings->paginate(config('app.pagination'))->withQueryString();

        return view('admin.meeting.index', [
            'pageTitle' => __('Meetings'),
            'meetings' => $meetings,
            'filters' => $filters,
            'isFiltered' => $isFiltered,
            'status' => $status,
            'dateRange' => $dateRange,
            'username' => $username,
        ]);
    }

    // Soft delete meeting
    public function delete($id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->delete();

        return redirect()->route('admin.meeting')->with('message', __('Meeting Deleted Successfully.'));
    }

    // Update meeting status via toggle
    public function updateStatus(Request $request)
    {
        $meeting = Meeting::find($request->meetingId);
        $meeting->status = $request->meetingStatus;
        $meeting->save();

        return response()->json(['success' => true, 'error' => '', 'data' => [], 'message' => __('Status Updated Successfully')]);
    }

    public function exportMeeting(Request $request)
    {
        $query = Meeting::with('user')->select('meeting_id', 'title', 'user_id', 'description', 'password', 'status', 'date', 'time', 'timezone', 'created_at');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('username')) {
            $username = $request->username;
            $query->whereHas('user', function ($usernameQuery) use ($username) {
                $usernameQuery->where('username', 'LIKE', '%' . $username . '%');
            });
        }

        if ($request->has('meeting_id')) {
            $query->where('meeting_id', 'like', '%' . $request->meeting_id . '%');
        }

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if ($request->has('created_date')) {
            $explodedDateRange = explode('-', $request->created_date);
            $startDate = trim($explodedDateRange[0]);
            $startDateWithTime = Carbon::createFromFormat('Y/m/d', $startDate)->format('Y-m-d') . " 00:00:00";

            $endDate = trim($explodedDateRange[1]);
            $endDateWithTime = Carbon::createFromFormat('Y/m/d', $endDate)->format('Y-m-d') . " 23:59:59";

            $query->whereBetween('created_at', [$startDateWithTime, $endDateWithTime]);
        }

        $meetings = $query->get();
        $arrayToExport = [];

        foreach ($meetings as $meeting) {
            $arrayToExport[] = [
                'Meeting ID' => $meeting->meeting_id,
                'Username' => $meeting->user->username,
                'Title' => $meeting->title,
                'Description' => $meeting->description,
                'Password' => $meeting->password,
                'Status' => $meeting->status,
                'Date' => $meeting->date,
                'Time' => $meeting->time,
                'Timezone' => $meeting->timezone,
                'Created_at' => $meeting->created_at,
            ];
        }
        if (empty($arrayToExport)) {
            return back()->with('error', 'No data available to export.');
        }

        return exportCSV('meetings.csv', $arrayToExport);
    }
}
