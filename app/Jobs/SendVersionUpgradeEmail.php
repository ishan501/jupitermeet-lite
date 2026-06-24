<?php

namespace App\Jobs;

use App\Mail\VersionUpgradeMail;
use App\Models\EmailTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendVersionUpgradeEmail implements ShouldQueue
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
        $template = EmailTemplate::where('slug', 'version-upgrade')->first();

        if ($template) {
            $body = str_replace(
                ['[VERSION]'],
                [$this->details['version']],
                $template->content
            );
            
            Mail::to($this->details['email'])->send(new VersionUpgradeMail($template->name, $body));
        }
    }
}
