<?php

namespace App\Listeners;

use App\Events\DigitalProductDownloadEvent;
use App\Traits\EmailTemplateTrait;


class DigitalProductDownloadListener
{
    use EmailTemplateTrait;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DigitalProductDownloadEvent $event): void
    {
        $this->sendMail($event);
    }

    private function sendMail(DigitalProductDownloadEvent $event): void
    {
        $email = $event->email;
        $data = $event->data;
        $this->sendingMail(sendMailTo: $email, userType: $data['userType'], templateName: $data['templateName'], data: $data);
    }
}
