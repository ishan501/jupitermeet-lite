<?php

namespace App\Http\Controllers\Admin;
use App\Jobs\SendVersionUpgradeEmail;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AdminController extends Controller
{
    public function signalingServer()
    {

        $url = getSetting('SIGNALING_URL');

        return view('admin.signaling-server.index', [
            'pageTitle' => __('Signaling Server'),
            'url' => $url,
        ]);

    }

    public function checkSignalingServer()
    {
        $status = __('Running');

        if (!getSignalingServerStatus()) {
            $status = __('Unreachable');
        }

        return response()->json([
            'success' => true,
            'error' => '',
            'status' => $status,
            'message' => __('Signaling Status Fetched'),
        ]);
    }

    public function manageUpdate()
    {
        if (getSetting('VERSION') == '1.8.8') {
            $settinngs = Setting::where('key', 'VERSION')->first();
            $settinngs->value = '2.0.0';
            $settinngs->save();
        }


        return view('admin.manage-update.index', [
            'pageTitle' => __('Manage Updates'),
        ]);
    }

    public function manageLicense()
    {
        return view('admin.manage-license.index', [
            'pageTitle' => __('Manage License'),
        ]);
    }
}