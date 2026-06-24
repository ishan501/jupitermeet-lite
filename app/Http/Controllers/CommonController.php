<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\App;
use App\Models\Meeting;
use Illuminate\Support\Facades\Session;


class CommonController extends Controller
{
    public function checkMeeting(Request $request)
    {
        if (getSetting('AUTH_MODE') == 'disabled') {
            return json_encode(['success' => true, 'id' => $request->id]);
        }

        $meeting = Meeting::where(['meeting_id' => $request->id, 'status' => 'active'])->first();
        $user = User::where(['username' => $request->id, 'status' => 'active'])->first();

        if ($meeting || $user) {
            return json_encode(['success' => true, 'id' => $request->id]);
        }

        return json_encode(['success' => false]);
    }

    public function setLocale(Request $request)
    {
        $locale = $request->locale;
        session(['locale' => $locale]);
        App::setLocale($locale);

        return redirect()->back();
    }

    //check details
    public function checkDetails()
    {
        return true;
    }

    public function toggleTheme(Request $request)
    {
        session()->put('theme', $request->theme_name);
        return redirect()->back();
    }
}
