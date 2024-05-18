<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Models\Document;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use App\Enums\HTTPResponseEnum;
use App\Models\ChatRequest;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class ChatMessageService
{
    public function validateUser(User $user, Chat $chat): void
    {
        if (
            !in_array($user->id, [$chat->sender_id, $chat->recipient_id], true)
        ) {
            abort(
                HTTPResponseEnum::FORBIDDEN,
                "You are not allowed to perform this operation"
            );
        }
    }

    public function validateChatSecret(
        User $user,
        Chat $chat,
        string $secret
    ): void {
        $role = $chat->sender_id === $user->id ? "sender" : "recipient";
        $other = $chat->sender_id === $user->id ? "recipient" : "sender";
        $secretKey = "{$role}_secret";
        $lockSecretKey = "{$role}_lock_secret";
        $chatLock = "{$role}_lock";
        $otherChatLock = "{$other}_lock";

        if (!Hash::check($secret, $chat->$secretKey)) {
            if (!Hash::check($secret, $chat->$lockSecretKey)) {
                abort(HTTPResponseEnum::FORBIDDEN, "Invalid secret provided");
            } else {
                $chat->$chatLock = 1;
                if ($chat->save() && $chat->$otherChatLock === 1) {
                    $this->triggerChatSelfDestruct($chat);
                    abort(
                        HTTPResponseEnum::FORBIDDEN,
                        "Self destruct! This chat is now being destroyed and you will not have ascess to it again"
                    );
                } else {
                    abort(HTTPResponseEnum::FORBIDDEN, "Chat locked");
                }
            }
        }
    }

    private function triggerChatSelfDestruct(Chat $chat)
    {
        $chat->chatMessages()->delete();
        $this->deleteChatRequest($chat);
        $chat->delete();
    }

    private function deleteChatRequest(Chat $chat)
    {
        $senderEmail = User::where("id", $chat->sender_id)->value("email");
        $recipientEmail = User::where("id", $chat->recipient_id)->value(
            "email"
        );
        $chatRequest = ChatRequest::where("sender_email", $senderEmail)
            ->where("recipient_email", $recipientEmail)
            ->first();
        // $chatRequest->status = 0;
        $chatRequest->delete();
    }

    public function processEncryptedMessage(Collection $chatMessages): array
    {
        $messages = $chatMessages
            ->map(function ($item) {
                $cryptoService = new CryptoService();
                $chat = Chat::find($item["chat_id"]);
                if (!$chat) {
                    throw new \Exception(
                        "Chat not found for message: {$item["uuid"]}"
                    );
                }
                $secret = $chat->chat_key;
                $encryptedContent = optional($item->messages->first())
                    ->encrypted_content;
                if (!$encryptedContent) {
                    throw new \Exception(
                        "Encrypted content not found for message: {$item["uuid"]}"
                    );
                }
                $plain = $cryptoService->decrypt($encryptedContent, $secret);
                return [
                    "user_id" => $item->messages[0]->user_id,
                    "uuid" => $item["uuid"],
                    "time" => $item["created_at"]->format("h:i: A"),
                    "message" => $plain,
                ];
            })
            ->all();
        return $messages;
    }

    public function storeChatMessage(Document $message, Chat $chat): ChatMessage
    {
        $data = [
            "uuid" => Str::uuid(),
            "message_uuid" => $message->uuid,
            "status" => 1,
        ];
        return $chat->chatMessages()->create($data);
    }
}
