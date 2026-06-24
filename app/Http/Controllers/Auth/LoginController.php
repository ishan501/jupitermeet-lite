<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Rules\ValidateReCaptcha;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Spatie\Activitylog\Models\Activity;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    //check if the user status is active or not
    protected function credentials(Request $request)
    {
        $this->validate($request, [
            'g-recaptcha-response' => [new RequiredIf(getSetting('CAPTCHA_LOGIN_PAGE') == 'enabled'), new ValidateReCaptcha]
        ]);

        $input = $request->email;
        $type = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [$type => $input, 'password' => $request->password, 'status' => 'active'];
    }
    protected function authenticated(Request $request, $user)
    {
        // Log user login activity
        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties(['key' => 'value'])
            ->event('Logged In')
            ->log('User');
    }
    public function logout(Request $request)
    {
        $user = $request->user();

        // Log user logout activity
        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties(['key' => 'value'])
            ->event('Logged Out')
            ->log('User');

        $locale = session('locale');
        $theme = session('theme');
        $this->guard()->logout();
        $request->session()->invalidate();
        if ($locale)
            session(['locale' => $locale]);

        if ($theme)
            session(['theme' => $theme]);

        Auth::logout();

        return redirect('/');

    }
}

