<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Jobs\SendTFAEmail;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\Models\UserCode;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['username', 'email', 'plan_id', 'status', 'tfa'])
            ->useLogName('User')
            ->logOnlyDirty();

    }

    public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
            'attributes' => array_merge(
                $activity->properties->get('attributes', []),
                [
                    'username' => $this->username,
                    'email' => $this->email,
                ]
            ),
        ]);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'plan_trial_ends_at' => 'datetime',
            'password' => 'hashed',
            'last_active' => 'datetime',
        ];
    }

    //relate user model to plan model using belongsTo
    public function plan()
    {
        return $this->belongsTo('App\Models\Plan');
    }

    //get user full name
    public function getFullNameAttribute($value)
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    //user meeting relation
    public function meeting()
    {
        return $this->hasMany('App\Models\Meeting');
    }

    public function planOnDefault()
    {
        return $this->plan_id == 1;
    }

    public function planSubscriptionCancel()
    {
        if ($this->plan_payment_gateway == 'paypal') {
            $httpClient = new HttpClient(['verify' => false]);

            $httpBaseUrl = 'https://' . (getSetting('PAYPAL_MODE') == 'sandbox' ? 'api-m.sandbox' : 'api-m') . '.paypal.com/';

            // Attempt to retrieve the auth token
            try {
                $payPalAuthRequest = $httpClient->request(
                    'POST',
                    $httpBaseUrl . 'v1/oauth2/token',
                    [
                        'auth' => [getSetting('PAYPAL_CLIENT_ID'), getSetting('PAYPAL_SECRET')],
                        'form_params' => [
                            'grant_type' => 'client_credentials',
                        ],
                    ]
                );

                $payPalAuth = json_decode($payPalAuthRequest->getBody()->getContents());
            } catch (BadResponseException $e) {
            }

            // Attempt to cancel the subscription
            try {
                $payPalSubscriptionCancelRequest = $httpClient->request(
                    'POST',
                    $httpBaseUrl . 'v1/billing/subscriptions/' . $this->plan_subscription_id . '/cancel',
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $payPalAuth->access_token,
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode([
                            'reason' => __('Cancelled'),
                        ]),
                    ]
                );
            } catch (BadResponseException $e) {
            }
        } elseif ($this->plan_payment_gateway == 'stripe') {
            // $stripe = new \Stripe\StripeClient(
            //     getSetting('STRIPE_SECRET')
            // );

            // // Attempt to cancel the current subscription
            // try {
            //     $stripe->subscriptions->update(
            //         $this->plan_subscription_id,
            //         ['cancel_at_period_end' => true]
            //     );
            // } catch (\Exception $e) {
            // }
        } elseif ($this->plan_payment_gateway == 'paystack') {
            // Attempt to cancel the current subscription
            try {
                $fields = [
                    'code' => $this->plan_subscription_id,
                    'token' => $this->email_token,
                ];
                $CancelSubscription = callCurlApiRequest('/subscription/disable', 'POST', $fields);
            } catch (\Exception $e) {
            }
        } elseif ($this->plan_payment_gateway == 'razorpay') {
            // Attempt to cancel the current subscription
            try {
                $fields = [
                    'cancel_at_cycle_end' => 0,
                ];
                $CancelSubscription = callCurlApiRequest('/subscription/' . $this->plan_subscription_id . '/cancel', 'POST', $fields, 'razorpay');
            } catch (\Exception $e) {
            }
        }

        $this->plan_ends_at = $this->plan_recurring_at;
        $this->plan_payment_gateway = null;
        $this->plan_recurring_at = null;
        $this->save();
    }

    //generate and send TFA code
    public function generateCode()
    {
        $code = rand(1000, 9999);

        UserCode::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['code' => $code]
        );

        try {
            $details = [
                'code' => $code,
            ];

            $user = auth()->user();

            SendTFAEmail::dispatch($user, $details['code']);

        } catch (\Exception $e) {
            info("Error: " . $e->getMessage());
        }
    }

    public function getCreatedAtCustomAttribute()
    {
        return convertToTimezone($this->created_at);
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }
}