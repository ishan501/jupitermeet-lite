<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAIChatbotSettingRequest;
use App\Http\Requests\UpdateApplicationSettingRequest;
use App\Http\Requests\UpdateBasicSettingRequest;
use App\Http\Requests\UpdateCompanyInformationRequest;
use App\Http\Requests\UpdateCssSettingRequest;
use App\Http\Requests\UpdateJsSettingRequest;
use App\Http\Requests\UpdateMeetingSettingRequest;
use App\Http\Requests\UpdateMollieRequest;
use App\Http\Requests\UpdateNodejsSettingRequest;
use App\Http\Requests\UpdatePaypalRequest;
use App\Http\Requests\UpdatePaystackRequest;
use App\Http\Requests\UpdateRazorpayRequest;
use App\Http\Requests\UpdateRecaptchaSettingRequest;
use App\Http\Requests\UpdateSmtpSettingRequest;
use App\Http\Requests\UpdateSocialLoginSettingRequest;
use App\Http\Requests\UpdateStripeRequest;
use App\Mail\TestSMTPMail;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // Show stripe payment gateway form
    public function stripePaymentGateway(Request $request)
    {
        return view('admin.payment-gateway.stripe', [
            'pageTitle' => __('Stripe'),
        ]);
    }

    // Show paypal payment gateway form
    public function paypalPaymentGateway(Request $request)
    {
        return view('admin.payment-gateway.paypal', [
            'pageTitle' => __('Paypal'),
        ]);
    }

    // Show paystack payment gateway form
    public function paystackPaymentGateway(Request $request)
    {
        return view('admin.payment-gateway.paystack', [
            'pageTitle' => __('Paystack'),
        ]);
    }

    // Show mollie payment gateway form
    public function molliePaymentGateway(Request $request)
    {
        return view('admin.payment-gateway.mollie', [
            'pageTitle' => __('Mollie'),
        ]);
    }

    // Show razorpay payment gateway form
    public function razorpayPaymentGateway(Request $request)
    {
        return view('admin.payment-gateway.razorpay', [
            'pageTitle' => __('Razorpay'),
        ]);
    }

    //save settings helper
    private function saveSettings(Request $request, $keys)
    {
        // Fetch all rows in one query
        $settings = Setting::whereIn('key', $keys)->get()->keyBy('key');

        DB::transaction(function () use ($request, $keys, $settings) {
            foreach ($keys as $key) {
                $setting = $settings->get($key);

                if ($setting) {
                    $setting->update(['value' => $request->input($key)]);
                }
            }
        });
    }

    // Show basic details form in setting module
    public function basic()
    {
        return view('admin.setting.basic', [
            'pageTitle' => __('Settings'),
        ]);
    }

    // Update basic details in settings module
    public function updateBasic(UpdateBasicSettingRequest $request)
    {
        $rows = [
            'APPLICATION_NAME',
            'PRIMARY_COLOR',
            'PRIMARY_LOGO',
            'SECONDARY_LOGO',
            'FAVICON',
        ];

        foreach ($rows as $row) {
            // Store logo and favicon in files
            if ($row == 'PRIMARY_LOGO' || $row == 'FAVICON' || $row == 'SECONDARY_LOGO') {
                $file = $request->file($row);
                if ($file && $file->isValid()) {
                    $filename = $row . '_' . now()->timestamp . '.png';

                    $globalconfigs = Setting::where('key', $row)->first();
                    if ($globalconfigs) {
                        $oldFilename = $globalconfigs->value;

                        if ($oldFilename && Storage::exists('images/' . $oldFilename)) {
                            Storage::delete('images/' . $oldFilename);
                        }

                        $globalconfigs->update(['value' => $filename]);
                    }

                    Storage::putFileAs('images', $file, $filename);
                }
                if ($request[$row]) {
                    activity('Setting')
                        ->causedBy(auth()->user())
                        ->withProperties([
                            'attributes' => [
                                'key' => $row,
                                'message' => "{$row} image has been updated.",
                            ],
                        ])
                        ->log("Updated {$row}");
                }
            } else {
                $globalconfigs = Setting::where('key', $row)->first();
                if (!empty($globalconfigs)) {
                    $globalconfigs->update(['value' => $request->input($row)]);
                }
            }
        }


        return back()->with('message', __('Settings saved.'));
    }

    // Show appliocation details form in setting module
    public function application()
    {
        return view('admin.setting.application', [
            'pageTitle' => __('Settings'),
        ]);
    }

    // Update application details in settings module
    public function updateApplication(UpdateApplicationSettingRequest $request)
    {
        $rows = [
            'AUTH_MODE',
            'COOKIE_CONSENT',
            'SOCIAL_INVITATION',
            'REGISTRATION',
            'VERIFY_USERS',
            'ADMIN_TIMEZONE',
        ];

        $this->saveSettings($request, $rows);

        return back()->with('message', __('Settings saved.'));
    }

    // Show meeting details form in setting module
    public function meeting()
    {
        return view('admin.setting.meeting', [
            'pageTitle' => __('Settings'),
        ]);
    }

    // Update meeting details in settings module
    public function updateMeeting(UpdateMeetingSettingRequest $request)
    {
        $rows = [
            'MODERATOR_RIGHTS',
            'DEFAULT_USERNAME',
            'SIGNALING_URL',
            'STUN_URL',
            'TURN_URL',
            'TURN_USERNAME',
            'TURN_PASSWORD',
        ];

        $this->saveSettings($request, $rows);

        return back()->with('message', __('Settings saved.'));
    }

    // Show meeting details form in setting module
    public function nodejs()
    {
        return view('admin.setting.nodejs', [
            'pageTitle' => __('Settings'),
        ]);
    }

    // Update meeting details in settings module
    public function updateNodejs(UpdateNodejsSettingRequest $request)
    {
        $rows = [
            'KEY_PATH',
            'CERT_PATH',
            'PORT',
        ];

        $this->saveSettings($request, $rows);

        foreach ($rows as $row) {
            $globalconfigs = Setting::where('key', $row)->first();
            if (!empty($globalconfigs)) {
                $globalconfigs->getModel()->update(['value' => $request->input($row)]);
            }
        }

        return back()->with('message', __('Settings saved.'));
    }

    public function aichatbot()
    {
        return view('admin.setting.aichatbot', [
            'pageTitle' => __('Settings'),
        ]);
    }

    // Show custom js form in setting module
    public function customJs()
    {
        return view('admin.setting.custom-js', [
            'pageTitle' => __('Settings'),
        ]);
    }

    // Show custom css form in setting module
    public function customCss()
    {
        return view('admin.setting.custom-css', [
            'pageTitle' => __('Settings'),
        ]);
    }

    // Show smtp form in setting module
    public function smtp()
    {
        return view('admin.setting.smtp', [
            'pageTitle' => __('Settings'),
        ]);
    }

    // Update smtp details in settings module
    public function updateSmtp(UpdateSmtpSettingRequest $request)
    {
        $rows = [
            'MAIL_MAILER',
            'MAIL_HOST',
            'MAIL_PORT',
            'MAIL_USERNAME',
            'MAIL_PASSWORD',
            'MAIL_ENCRYPTION',
            'MAIL_FROM_ADDRESS',
        ];

        $this->saveSettings($request, $rows);

        return back()->with('message', __('Settings saved.'));
    }

    // Test SMTP 
    public function testSmtp(Request $request)
    {
        try {
            $emailBody = EmailTemplate::where('slug', 'test-smtp')->first();
            Mail::to($request->email)->send(new TestSMTPMail($emailBody->name, $emailBody['content']));
            return json_encode(['success' => true]);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }


    // Show google recaptcha details form in setting module
    public function googleRecaptcha()
    {
        return view('admin.setting.google-recaptcha', [
            'pageTitle' => __('Settings'),
        ]);
    }

    // Show company information form in setting module
    public function companyInformation()
    {
        $countries = Country::all();
        return view('admin.setting.company-information', [
            'pageTitle' => __('Settings'),
            'countries' => $countries,
        ]);
    }

    // Show social login details form in setting module
    public function socialLogin()
    {
        return view('admin.setting.social-login', [
            'pageTitle' => __('Settings'),
        ]);
    }
}