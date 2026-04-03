<?php

namespace App\Listeners;

use App\Events\DeliverymanPasswordResetEvent;
use App\Traits\EmailTemplateTrait;


class DeliverymanPasswordResetListener
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
    public function handle(DeliverymanPasswordResetEvent $event): void
    {
        $this->sendMail($event);
    }

    private function sendMail(DeliverymanPasswordResetEvent $event):void{
        $email = $event->email;
        $data = $event->data;
        $this->sendingMail(sendMailTo: $email,userType: $data['userType'],templateName: $data['templateName'],data: $data);
    }
}
