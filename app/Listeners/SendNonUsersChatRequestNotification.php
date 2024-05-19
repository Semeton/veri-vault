<?php

namespace App\Listeners;

use App\Events\NonUsersChatRequest;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNonUsersChatRequestNotification
{
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
    public function handle(NonUsersChatRequest $event): void
    {
        $emailService = new EmailService();
        $emailService->sendNonUsersChatRequestEmail($event->to, $event->email);
    }
}
