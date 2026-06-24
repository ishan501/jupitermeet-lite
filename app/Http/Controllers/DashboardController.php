<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckTFA;
use App\Http\Middleware\CustomVerified;
use App\Jobs\SendMeetingInvitationEmail;
use App\Models\Contact;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(CheckTFA::class);

        if (getSetting('VERIFY_USERS') == 'enabled') {
            $this->middleware(CustomVerified::class);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $meetings = Meeting::where('user_id', Auth::id())
            ->where('status', 'active')
            ->where('meeting_id', '!=', Auth::user()->username);

        if ($request->search) {
            $meetings->where(function ($query) use ($request) {
                $query->where('title', 'like', "%" . $request->search . "%")
                    ->orWhere('description', 'like', "%" . $request->search . "%")
                    ->orWhere('meeting_id', 'like', "%" . $request->search . "%");
            });
        }

        $meetings = $meetings->orderBy('id', 'DESC')->paginate(config('app.pagination'));

        $contacts = Contact::where('user_id', Auth::id())->get();

        return view('dashboard', [
            'page' => __('Dashboard'),
            'meetings' => $meetings,
            'firstMeeting' => !$meetings->isEmpty() ? $meetings[0] : [],
            'timezones' => json_decode(file_get_contents(public_path() . '/sources/timezones.json'), true),
            'timeLimit' => getUserPlanFeatures(Auth::id())->time_limit,
            'contacts' => $contacts,
            'search' => $request->search,
        ]);
    }

    //create a new meeting
    public function createMeeting(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required|unique:meetings',
            'title' => 'required|min:3|max:100',
            'description' => 'max:1000',
            'password' => 'nullable|min:4|max:8',
            'date' => '',
            'time' => '',
            'timezone' => 'max:100'
        ]);

        $allowedMeetings = getUserPlanFeatures(getAuthUserInfo('id'))->meeting_no;
        if ($allowedMeetings != -1 && count(getAuthUserInfo('meeting')) >= $allowedMeetings) {
            return json_encode(['success' => false, 'error' => __('You have reached the maximum meeting creation limit. Upgrade now')]);
        }

        $meeting = new Meeting();
        $meeting->meeting_id = $request->meeting_id;
        $meeting->title = $request->title;
        $meeting->description = $request->description;
        $meeting->user_id = Auth::id();
        $meeting->password = $request->password;
        $meeting->date = $request->date;
        $meeting->time = $request->time;
        $meeting->timezone = $request->timezone ?? "";

        if ($meeting->save()) {
            $meeting->date = formatDate($meeting->date);
            $meeting->time = formatTime($meeting->time);

            return json_encode(['success' => true, 'data' => $meeting]);
        }

        return json_encode(['success' => false, 'error' => __('Something went wrong')]);
    }

    //edit a meeting
    public function editMeeting(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'max:1000',
            'password' => 'nullable|min:4|max:8',
            'date' => '',
            'time' => '',
            'timezone' => 'max:100'
        ]);

        $meeting = Meeting::find($request->id);
        $meeting->title = $request->title;
        $meeting->description = $request->description;
        $meeting->password = $request->password;
        $meeting->date = $request->date;
        $meeting->time = $request->time;
        $meeting->timezone = $request->timezone ?? "";

        if ($meeting->save()) {
            $meeting->date = formatDate($meeting->date);
            $meeting->time = formatTime($meeting->time);
            return json_encode(['success' => true, 'data' => $meeting]);
        }

        return json_encode(['success' => false, 'error' => __('Something went wrong')]);
    }

    //delete a meeting
    public function deleteMeeting(Request $request)
    {
        $meeting = Meeting::find($request->id);
        $userId = $meeting->user_id;

        if ($meeting->delete()) {

            $meetingCount = Meeting::where('user_id', $userId)->count();
            if ($meetingCount > 0) {
                return json_encode(['success' => true, 'noMeetingCount' => false]);
            } else {
                return json_encode(['success' => true, 'noMeetingCount' => true]);
            }
        }

        return json_encode(value: ['success' => false, 'error' => __('Something went wrong.')]);
    }

    public function getInvites(Request $request)
    {
        $meeting = Meeting::find($request->id);

        if ($meeting) {
            return json_encode(['success' => true, 'data' => $meeting->invites ? explode(',', $meeting->invites) : []]);
        }

        return json_encode(['success' => false]);
    }

    public function sendInvite(Request $request)
    {
        $newEmails = json_decode($request->emails, true);

        $newValidEmails = array_filter($newEmails, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });

        if (!sizeof($newValidEmails))
            return json_encode(['success' => false, 'error' => __('Something went wrong')]);

        $meeting = Meeting::find($request->id);
        $oldEmails = !empty($meeting->invites) ? explode(',', $meeting->invites) : [];

        $allEmails = array_unique(array_merge($oldEmails, $newValidEmails), SORT_REGULAR);
        $meeting->invites = implode(',', $allEmails);

        if ($meeting->save()) {
            SendMeetingInvitationEmail::dispatch($meeting, $newValidEmails);
            return json_encode(['success' => true, 'message' => __('Invitation has been sent')]);
        }

        return json_encode(['success' => false, 'error' => __('Something went wrong')]);
    }
}
