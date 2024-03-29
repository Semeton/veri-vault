<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Models\Document;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use App\Enums\HTTPResponseEnum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class ChatMessageService {
    public function validateUser(User $user, Chat $chat): void {
        if (!in_array($user->id, [$chat->sender_id, $chat->recipient_id], true)) {
            abort(HTTPResponseEnum::FORBIDDEN, 'You are not allowed to perform this operation');
        }
    }

    public function validateChatSecret(User $user, Chat $chat, string $secret): void
    {
        $role = $chat->sender_id === $user->id ? 'sender' : 'recipient';
        $secretKey = "{$role}_secret";
        $lockSecretKey = "{$role}_lock_secret";

        if (!Hash::check($secret, $chat->$secretKey)) {
            if (!Hash::check($secret, $chat->$lockSecretKey)) {
                abort(HTTPResponseEnum::FORBIDDEN, 'Invalid secret provided');
            } else {
                // $this->triggerChatSelfDestruct();
                abort(HTTPResponseEnum::FORBIDDEN, 'Chat locked');
            }
        }
    }

    public function processEncryptedMessage(Collection $chatMessages): array
    {
        $messages = $chatMessages->map(function ($item) {
            $cryptoService = new CryptoService;
            $chat = Chat::find($item['chat_id']);
            if (!$chat) {
                throw new \Exception("Chat not found for message: {$item['uuid']}");
            }
            $secret = $chat->chat_key;
            $encryptedContent = optional($item->messages->first())->encrypted_content;
            if (!$encryptedContent) {
                throw new \Exception("Encrypted content not found for message: {$item['uuid']}");
            }
            $plain = $cryptoService->decrypt($encryptedContent, $secret);
            return [
                'user_id' => $item->messages[0]->user_id,
                'uuid' => $item['uuid'],
                'message' => $plain,
            ];
        })->all();
        return $messages;
    }

    public function storeChatMessage(Document $message, Chat $chat): ChatMessage
    {
        $data = [
            'uuid' => Str::uuid(),
            'message_uuid' => $message->uuid,
            'status' => 1
        ];

        return $chat->chatMessages()->create($data);
    }
}