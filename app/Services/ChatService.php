<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Enums\HTTPResponseEnum;

class ChatService
{
    /**
     * Handles exceptions thrown during chat operations.
     *
     * @param Throwable $th The thrown exception.
     * @param string|null $uuid Optional UUID of the chat for not found exceptions.
     * @return Chat $chat with the requested uuid
     */
    public function validateUuid(User $user, string $uuid)
    {
        // $chat = $user->chats()->where("uuid", $uuid)->get();
        $chat = Chat::where("uuid", $uuid)->first();
        if (!$chat) {
            abort(
                HTTPResponseEnum::BAD_REQUEST,
                "Chat has gone through the selfdetruct mechanism"
            );
        }

        $role = $chat->sender_id === $user->id ? "sender" : "recipient";
        $lock = "{$role}_lock";

        if ($chat->$lock === 1) {
            abort(HTTPResponseEnum::BAD_REQUEST, "Access denied. Chat locked");
        }

        if ($chat->status === 2) {
            abort(
                HTTPResponseEnum::BAD_REQUEST,
                "Access denied. Chat destroyed"
            );
        }
        return $chat;
    }

    public function setUserChatSecret(
        User $user,
        Chat $chat,
        string $secret
    ): void {
        if ($chat->sender_id === $user->id) {
            $chat->update([
                "sender_secret" => bcrypt($secret),
                "sender_lock_secret" => bcrypt(strrev($secret)),
            ]);
        } else {
            $chat->update([
                "recipient_secret" => bcrypt($secret),
                "recipient_lock_secret" => bcrypt(strrev($secret)),
            ]);
        }
    }
}
