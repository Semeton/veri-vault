<?php

namespace App\Listeners;

use App\Enums\ChatActivityEnum;
use App\Events\ChatActivity;
use App\Services\ChatActivityService;
use App\Services\ChatService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChatActivityNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(public ChatService $chatService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ChatActivity $event): void
    {
        $chat = $this->chatService->getParties($event->chat);
        $type = $event->type;

        switch ($type) {
            case ChatActivityEnum::CREATED:
                ChatActivityService::sendChatCreationNotification($chat);
                break;
            case ChatActivityEnum::LOCKED:
                ChatActivityService::sendChatLockNotification($chat);
                break;
            case ChatActivityEnum::UNLOCKED:
                ChatActivityService::sendChatUnLockNotification($chat);
                break;
            case ChatActivityEnum::DESTROYED:
                ChatActivityService::sendChatSelfDestructNotification($chat);
                break;
            default:
                break;
        }
    }
}
