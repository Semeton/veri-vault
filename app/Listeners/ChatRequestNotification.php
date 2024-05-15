<?php

namespace App\Listeners;

use App\Events\ChatRequest;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChatRequestNotification
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
    public function handle(ChatRequest $event): void
    {
        $user = $event->user;
        $senderEmail = $event->senderEmail;
        $mailData = [
            "name" => $user->name,
            "email" => $user->email,
            "senderEmail" => $senderEmail,
        ];

        $emailService = new EmailService();
        $emailService->sendChatRequestEmail($mailData);
    }
}
