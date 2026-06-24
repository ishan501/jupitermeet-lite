<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckTFA;
use App\Http\Middleware\CustomVerified;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateProfileBasicRequest;
use App\Jobs\SendCancelSubscriptionEmail;
use App\Models\ApiToken;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(CheckTFA::class);

        if (getSetting('VERIFY_USERS') == 'enabled') {
            $this->middleware(CustomVerified::class);
        }
    }

    // get basic profile information
    public function basic()
    {
        $user = Auth::user();
        return view('user.profile.basic', [
            'pageTitle' => __('Profile'),
            'user' => $user,
        ]);
    }

    // update basic profile information
    public function updateBasic(UpdateProfileBasicRequest $request)
    {
        try {

            $request->user()->first_name = $request->first_name;
            $request->user()->last_name = $request->last_name;
            $request->user()->username = $request->username;
            $request->user()->timezone = $request->timezone;

            $request->user()->save();

            return back()->with('message', __('Settings saved.'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // upload your profile image
    public function uploadAvatar(Request $request)
    {
        try {
            $avatarPath = storage_path('app/public/avatars/');

            if (!File::exists($avatarPath)) {
                File::makeDirectory($avatarPath);
            }

            if ($request->user()->avatar && File::exists($avatarPath . $request->user()->avatar)) {
                unlink(storage_path('app/public/avatars/' . $request->user()->avatar));
            }

            activity('User')
                ->causedBy(auth()->user())
                ->withProperties([
                    'attributes' => [
                        'key' => 'Profile image Updated',
                        'message' => "Profile image has been updated.",
                    ],
                ])
                ->log("Updated Profile image");

            $image = $request->image;
            $imageName = uniqid() . '.' . $request->extension;
            $image->move(storage_path('app/public/avatars/'), $imageName);
            $request->user()->avatar = $imageName;

            activity()->disableLogging();
            $request->user()->save();
            activity()->enableLogging();

            return response()->json(['success' => true, 'message' => __('Data updated successfully')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => true, 'message' => __('Something went wrong')]);
        }
    }

    // delete your profile image
    public function deleteAvatar(Request $request)
    {
        unlink(storage_path('app/public/avatars/' . $request->user()->avatar));

        activity('User')
            ->causedBy(auth()->user())
            ->withProperties([
                'attributes' => [
                    'key' => 'Profile image Deleted',
                    'message' => "Profile image has been deleted.",
                ],
            ])
            ->log("Deleted Profile image");

        $request->user()->avatar = null;

        activity()->disableLogging();
        $request->user()->save();
        activity()->enableLogging();

        return json_encode(['success' => true, 'id' => $request->id]);
    }

    // get change password page
    public function security()
    {
        $user = Auth::user();
        return view('user.profile.security', [
            'pageTitle' => __('Profile'),
            'user' => $user,
        ]);
    }

    // update your password
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('The current password is incorrect.')]);
        }

        activity('User')
            ->causedBy(auth()->user())
            ->withProperties([
                'attributes' => [
                    'key' => 'Password Updated',
                    'message' => "Password has been updated.",
                ],
            ])
            ->log("Password Updated");

        activity()->disableLogging();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Auth::logoutOtherDevices($request->password);
        activity()->enableLogging();

        return back()->with('message', __('Settings saved.'));
    }

    // view your plan details
    public function plan()
    {
        $user = Auth::user();
        return view('user.profile.plan', [
            'pageTitle' => __('Profile'),
            'user' => $user,
        ]);
    }

    // view your payment history
    public function payment(Request $request)
    {
        $payments = Payment::where('user_id', $request->user()->id)
            ->orderBy('id', 'DESC')->paginate(config('app.pagination'));

        $plans = Plan::where([['amount_month', '>', 0], ['amount_year', '>', 0]])->withTrashed()->get();

        return view('user.profile.payment', ['payments' => $payments, 'plans' => $plans, 'page' => __('Payments'), 'pageTitle' => 'Payments']);
    }


    // view your API token
    public function apiToken(Request $request)
    {
        $apiTokens = ApiToken::where('user_id', Auth::user()->id)->paginate();
        return view('user.profile.api-token.index', ['apiTokens' => $apiTokens, 'pageTitle' => 'Api Tokens']);
    }

    // view your contacts list
    public function contacts()
    {
        $contacts = Contact::where('user_id', Auth::user()->id)
            ->orderBy('id', 'DESC')->paginate(config('app.pagination'));

        return view('user.profile.contact.index', ['contacts' => $contacts, 'pageTitle' => __('Contacts')]);
    }

    // view two factor authentication settings
    public function tfa(Request $request)
    {
        return view('user.profile.tfa', ['user' => Auth::user(), 'pageTitle' => __('Two Factor Authentication')]);
    }

    // update two factor authentication settings
    public function updateTfa(Request $request)
    {
        if (!getSetting('MAIL_USERNAME') && $request->userTfa == 'active')
            return response()->json(['success' => false, 'error' => true, 'data' => [], 'message' => __('SMTP settings are missing. Please try again later.')]);

        $user = User::find($request->userId);
        $user->tfa = $request->userTfa;
        $user->save();

        Session::put('user_tfa', auth()->user()->id);

        return response()->json(['success' => true, 'error' => '', 'data' => [], 'message' => __('Two factor Authentication Updated Successfully')]);
    }

    // view delete account page
    public function deleteAccount(Request $request)
    {
        return view('user.profile.delete-account', ['pageTitle' => __('Delete Account')]);
    }

    // soft delete your account
    public function delete()
    {
        $user = Auth::user();
        $user->delete();

        return redirect()->route('user.profile.delete-account')->with('message', __('Your account has been deleted.'));

    }

}
