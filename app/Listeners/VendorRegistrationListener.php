<?php

namespace App\Listeners;

use App\Events\VendorRegistrationEvent;
use App\Traits\EmailTemplateTrait;

class VendorRegistrationListener
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
    public function handle(VendorRegistrationEvent $event): void
    {
        $this->sendMail($event);
    }

    private function sendMail(VendorRegistrationEvent $event):void{
        $email = $event->email;
        $data = $event->data;
        $this->sendingMail(sendMailTo: $email,userType: $data['userType'],templateName: $data['templateName'],data: $data);
    }
}
