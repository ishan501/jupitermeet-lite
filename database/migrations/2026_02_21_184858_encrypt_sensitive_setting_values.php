<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up(): void
    {
        $keys = [
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

        $settings = DB::table('settings')
            ->whereIn('key', $keys)
            ->get();

        foreach ($settings as $setting) {
            if (!empty($setting->value)) {
                DB::table('settings')
                    ->where('id', $setting->id)
                    ->update([
                        'value' => Crypt::encryptString($setting->value),
                    ]);
            }
        }
    }

    public function down(): void
    {
        // Leave empty intentionally
    }
};