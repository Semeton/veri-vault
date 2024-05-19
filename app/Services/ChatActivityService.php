<?php

namespace App\Services;

use App\Enums\ChatActivityEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ChatActivityService
{
    public static function sendChatCreationNotification($chat)
    {
        $user = Auth::user();
        $group = [
            [
                "to" => $user->email,
                "name" => $user->name,
                "type" => "New Chat Created",
                "message" => ChatActivityEnum::getChatCreatedMessage(
                    $chat->other["name"]
                ),
            ],
            [
                "to" => $chat->other["email"],
                "name" => $chat->other["name"],
                "type" => "Chat Created",
                "message" => ChatActivityEnum::getChatCreatedMessage(
                    $user->name
                ),
            ],
        ];

        foreach ($group as $data) {
            EmailService::sendChatChatActivityEmail($data);
        }
    }

    public static function sendChatLockNotification($chat)
    {
        $user = Auth::user();
        $group = [
            [
                "to" => $user->email,
                "name" => $user->name,
                "type" => "Chat Locked",
                "message" => ChatActivityEnum::getChatLockedMessageSelf(
                    $chat->other["name"]
                ),
            ],
            [
                "to" => $chat->other["email"],
                "name" => $chat->other["name"],
                "type" => "Chat Locked",
                "message" => ChatActivityEnum::getChatLockedMessagePartner(
                    $user->name
                ),
            ],
        ];

        foreach ($group as $data) {
            EmailService::sendChatChatActivityEmail($data);
        }
    }

    public static function sendChatUnLockNotification($chat)
    {
        $user = Auth::user();
        $group = [
            [
                "to" => $user->email,
                "name" => $user->name,
                "type" => "Chat Unlocked",
                "message" => ChatActivityEnum::getChatUnlockMessageSelf(
                    $chat->other["name"]
                ),
            ],
            [
                "to" => $chat->other["email"],
                "name" => $chat->other["name"],
                "type" => "Chat Unlocked",
                "message" => ChatActivityEnum::getChatUnlockMessagePartner(
                    $user->name
                ),
            ],
        ];

        foreach ($group as $data) {
            EmailService::sendChatChatActivityEmail($data);
        }
    }

    public static function sendChatSelfDestructNotification($chat)
    {
        $user = Auth::user();
        $group = [
            [
                "to" => $user->email,
                "name" => $user->name,
                "type" => "Chat Destroyed",
                "message" => ChatActivityEnum::getChatSelfDestructMessage(
                    $chat->other["name"]
                ),
            ],
            [
                "to" => $chat->other["email"],
                "name" => $chat->other["name"],
                "type" => "Chat Destroyed",
                "message" => ChatActivityEnum::getChatSelfDestructMessage(
                    $user->name
                ),
            ],
        ];

        foreach ($group as $data) {
            EmailService::sendChatChatActivityEmail($data);
        }
    }
}
