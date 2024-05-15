<?php

namespace App\Listeners;

use App\Events\NewUserRegistration;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleNewUserRegistration
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
    public function handle(NewUserRegistration $event): void
    {
        $user = $event->user;
        $mailData = [
            "name" => $user->name,
            "email" => $user->email,
        ];
        $emailService = new EmailService();
        $emailService->sendWelcomeEmail($mailData);
    }
}
