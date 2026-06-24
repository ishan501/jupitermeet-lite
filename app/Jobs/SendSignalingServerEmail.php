<?php

namespace App\Jobs;

use App\Mail\SignalingServelMail;
use App\Models\EmailTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendSignalingServerEmail implements ShouldQueue
{
    use Queueable;

    protected $details;

    /**
     * Create a new job instance.
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $template = EmailTemplate::where('slug', 'ping-signaling-server')->first();

        if ($template) {
            $body = str_replace(
                ['[SIGNALING_URL]'],
                [$this->details['signaling_url']],
                $template->content
            );

            Mail::to($this->details['email'])->send(new SignalingServelMail($template->name, $body));
        }
    }
}
