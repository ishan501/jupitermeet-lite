<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Setting extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'settings';

    protected $fillable = ['key', 'value', 'description'];

    // Keys whose values must be encrypted
    protected array $encryptedKeys = [
        'STRIPE_SECRET',
        'STRIPE_WH_SECRET',
        'RAZORPAY_SECRET_KEY',
        'PAYSTACK_SECRET_KEY',
        'PAYPAL_SECRET',
        'MOLLIE_API_KEY',
        'MAIL_PASSWORD',
        'GOOGLE_RECAPTCHA_SECRET',
        'GOOGLE_CLIENT_SECRET',
        'FACEBOOK_CLIENT_SECRET',
        'LINKEDIN_CLIENT_SECRET',
        'TWITTER_CLIENT_SECRET',
        'TURN_PASSWORD',
        'AI_CHATBOT_API_KEY'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['key', 'value'])
            ->useLogName(logName: 'Setting')
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
            'attributes' => array_merge(
                $activity->properties->get('attributes', []),
                [
                    'key' => $this->key,
                    'value' => $this->value,
                ]
            ),
        ]);
    }

    protected static function booted()
    {
        parent::boot();
        
        static::saved(function ($setting) {
            Cache::forget('settings');
        });

        static::deleted(function ($setting) {
            Cache::forget('settings');
        });
    }

    //setter function
    public function setValueAttribute($value)
    {
        // if key is set and it's in encrypted list, encrypt the value
        if (!empty($this->attributes['key']) && in_array($this->attributes['key'], $this->encryptedKeys, true)) {
            $this->attributes['value'] = $value ? Crypt::encryptString($value) : null;
            return;
        }

        $this->attributes['value'] = $value;
    }

    //getter function
    public function getValueAttribute($value)
    {
        if (!empty($this->attributes['key']) && in_array($this->attributes['key'], $this->encryptedKeys, true)) {
            try {
                return $value ? Crypt::decryptString($value) : null;
            } catch (DecryptException $e) {
                // Backward compatible: value is plaintext (old data)
                return $value;
            }
        }

        return $value;
    }
    
}