<?php
namespace App\Jobs;

use App\Mail\PaymentStatusMail;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPaymentStatusEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payment;

    /**
     * Create a new job instance.
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if ($this->payment->status == 'completed') {
            $emailTemplate = EmailTemplate::where('slug', 'payment-status-success')->first();
        } else {
            $emailTemplate = EmailTemplate::where('slug', 'payment-status-fail')->first();
        }

        if ($emailTemplate) {
            Mail::to($this->payment->user->email)->send(
                new PaymentStatusMail($emailTemplate->name, $emailTemplate->content)
            );
        }
    }
}