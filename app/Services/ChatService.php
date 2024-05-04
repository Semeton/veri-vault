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
    public function validateUuid(User $user, string $uuid): Chat
    {
        $chat = $user->chats()->where("uuid", $uuid)->first();
        if (!$chat) {
            abort(HTTPResponseEnum::NOT_FOUND, "Chat does not exist");
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
