<?php

namespace App\Console\Commands;

use App\Jobs\SendSignalingServerEmail;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class PingSignalingServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ping-signaling-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify admin when the signaliing server is down';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (getSignalingServerStatus()) {

            // If the signaling server is up, we ensure that the SEND_SIGNALING_MESSAGE setting is enabled
            if (getSetting('SEND_SIGNALING_MESSAGE') == 0) {

                // Update the setting to true if it was previously false
                $settings = Setting::where('key', 'SEND_SIGNALING_MESSAGE')->first();
                $settings->getModel()->update(['value' => true]);
            }
        } else {

            // If the signaling server is down, we disable the SEND_SIGNALING_MESSAGE setting and notify the admin
            if (getSetting('SEND_SIGNALING_MESSAGE') == 1) {

                // Update the SEND_SIGNALING_MESSAGE setting to false
                $settings = Setting::where('key', 'SEND_SIGNALING_MESSAGE')->first();
                $settings->getModel()->update(['value' => false]);

                // Notify the admin via email
                if (getSetting('SIGNALING_URL')) {
                    $adminEmail = User::where('role', 'admin')->first()->email;
                    $details = [
                        'email' => $adminEmail,
                        'signaling_url' => getSetting('SIGNALING_URL'),
                    ];

                    SendSignalingServerEmail::dispatch($details);
                }
            }
        }
    }
}
