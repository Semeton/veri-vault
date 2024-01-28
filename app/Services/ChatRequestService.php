<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatRequest;
use Illuminate\Support\Str;

class ChatRequestService {

    public function createChat(ChatRequest $chatRequest)
    {
        // $senderId = User::where('email', $chatRequest->sender_email)->value('id');
        // $recipientId = User::where('email', $chatRequest->recipient_email)->value('id');
        $senderId = $chatRequest->sender()->value('id');
        $recipientId = $chatRequest->recipient()->value('id');
        $chatKey = $this->generateChatKey($chatRequest->sender_email, $chatRequest->recipient_email);

        return Chat::create([
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
            'uuid' => Str::uuid(),
            'chat_key' => $chatKey,
        ]);
    }

    public function generateChatKey(string $sender_email, string $recipient_email)
    {
        $length = strlen($sender_email) + strlen($recipient_email);
        return bin2hex(random_bytes($length));
    }
}