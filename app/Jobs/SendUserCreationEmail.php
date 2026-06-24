<?php
namespace App\Jobs;

use App\Mail\UserCreationMail;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendUserCreationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new job instance.
     *
     * @param array $details
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $template = EmailTemplate::where('slug', 'user-creation')->first();

        if ($template) {
            $body = str_replace(
                ['[USER_NAME]', '[USER_EMAIL]', '[USER_PASSWORD]'],
                [$this->details['username'], $this->details['email'], $this->details['password']],
                $template->content
            );

            Mail::to($this->details['email'])->send(new UserCreationMail($template->name, $body));
        }
    }
}