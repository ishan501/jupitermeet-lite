<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckAuthMode;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendWelcomeEmail;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware(CheckAuthMode::class);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public function register(RegisterRequest $request)
    {
        activity()->disableLogging();
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        try {
            $ip = getVisIpAddr();
            $country = getCountryByIp($ip);

            if ($country->geoplugin_countryCode) {
                $country = Country::where('code', strtolower($country->geoplugin_countryCode))->first();
                $user->country_id = $country->id;
                $user->save();
            }
        } catch (\Exception $e) {
        }

        activity()->enableLogging();

        $details = [
            'username' => $user->username,
            'email' => $user->email,
        ];

        SendWelcomeEmail::dispatch($details);

        if (getSetting('VERIFY_USERS') == 'enabled') {
            $user->sendEmailVerificationNotification();
        }

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties(['key' => 'value'])
            ->event('Registered')
            ->log('User');

        Auth::login($user);

        return redirect()->route('dashboard');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);


    }

    //redirected to add username page
    public function username(Request $request)
    {
        // if ($request->session()->has('sociallogindata')) {
        return view('auth.add-username', [
            'page' => __('Set Username'),
        ]);
        // } else {
        //     return redirect()->route('home');
        // }
    }

    public function usernameVerify(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:20', 'unique:users', 'alpha_dash']
        ]);

        $data = $request->session()->get('sociallogindata');

        $user = new User();
        $user->username = $request->get('username');
        $user->email = $data->email;
        $user->password = Hash::make(Str::random(6));
        $user->google_id = $data->social && $data->social == 'google' ? $data->id : null;
        $user->facebook_id = $data->social && $data->social == 'facebook' ? $data->id : null;
        $user->twitter_id = $data->social && $data->social == 'twitter' ? $data->id : null;
        $user->linkedin_id = $data->social && $data->social == 'linkedin' ? $data->id : null;
        if (isset($data->avatar) && $data->avatar != '') {
            $contents = file_get_contents($data->avatar);
            $name = substr($data->avatar, strrpos($data->avatar, '/') + 1) . '.png';
            Storage::put('public/avatars/' . $name, $contents);
            $user->avatar = $name;
        }

        $user->save();

        Session::forget('sociallogindata');

        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
