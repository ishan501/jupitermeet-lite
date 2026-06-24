<?php
namespace App\Jobs;

use App\Mail\TFAEmail;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTFAEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $code;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $template = EmailTemplate::where('slug', 'two-factor-auth-code')->first();

        if ($template) {
            $body = str_replace('[CODE]', $this->code, $template->content);

            Mail::to($this->user->email)->send(new TFAEmail($template->name, $body));
        }
    }
}