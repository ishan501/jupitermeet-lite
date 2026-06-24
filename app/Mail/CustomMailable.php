<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

abstract class CustomMailable extends Mailable
{
    public function send($mailer)
    {
        if (!getSetting('MAIL_USERNAME')) {
            return;
        }

        parent::send($mailer);
    }

    public function queue($mailer)
    {
        if (!getSetting('MAIL_USERNAME')) {
            return;
        }

        parent::queue($mailer);
    }
}
