<?php

namespace App\Listeners;

use App\Events\OrderEditEvent;
use App\Events\OrderEditReturnPaymentEvent;
use App\Traits\EmailTemplateTrait;
use App\Traits\PushNotificationTrait;


class OrderEditReturnPaymentListener
{
    use PushNotificationTrait, EmailTemplateTrait;

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
    public function handle(OrderEditReturnPaymentEvent $event): void
    {
        if ($event->notification) {
            $this->sendNotification($event);
        }
    }

    private function sendNotification(OrderEditReturnPaymentEvent $event): void
    {
        $key = $event->notification->key;
        $type = $event->notification->type;
        $order = $event->notification->order;
        $this->sendOrderNotification(key: $key, type: $type, order: $order);
    }
}
