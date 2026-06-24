<?php
namespace App\Jobs;

use App\Mail\MeetingInvitationMail;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMeetingInvitationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $meeting;
    protected $emails;

    /**
     * Create a new job instance.
     *
     * @param $meeting
     * @param array $emails
     */
    public function __construct($meeting, array $emails)
    {
        $this->meeting = $meeting;
        $this->emails  = $emails;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $template = EmailTemplate::where('slug', 'meeting-invitation')->first();

        if ($template) {
            foreach ($this->emails as $email) {
                $body = str_replace(
                    ['[USER_NAME]', '[MEETING_ID]', '[MEETING_TITLE]', '[MEETING_PASSWORD]', '[MEETING_DATE]', '[MEETING_TIME]', '[MEETING_TIMEZONE]', '[MEETING_DESCRIPTION]', '[MEETING_LINK]'],
                    [
                        $this->meeting->user->username,
                        $this->meeting->meeting_id,
                        $this->meeting->title,
                        $this->meeting->password,
                        $this->meeting->date,
                        $this->meeting->time,
                        $this->meeting->timezone,
                        $this->meeting->description,
                        route('meeting', ['id' => $this->meeting->meeting_id]),
                        $email,
                    ],
                    $template->content
                );

                Mail::to($email)->send(new MeetingInvitationMail($template->name, $body));
            }
        }
    }
}