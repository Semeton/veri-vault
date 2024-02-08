<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\HTTPResponseEnum;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Validates the incoming request against the provided rules.
     * 
     * @param Request $request Incoming request to validate.
     * @param array $rules Validation rules.
     * @return array Validated data.
     */
    public function validateRequest(Request $request, array $rules): array
    {
        return $request->validate($rules);
    }

    /**
     * Validates the chat request data for business logic.
     * 
     * @param array $data Data to validate.
     * @return void
     */
    public function validateChatRequest(User $user, array $data): void
    {
        if ($user->email === $data['recipient_email']) {
            abort(HTTPResponseEnum::FORBIDDEN, 'You cannot send a chat request to yourself');
        }

        if (Chat::where('sender_id', $user->id)->where('recipient_id', User::where('email', $data['recipient_email'])->first()->id)->exists()) {
            abort(HTTPResponseEnum::BAD_REQUEST, 'An active chat is already established between you and this user');
        }

        $requestExists = $user->sentChatRequests()->where('recipient_email', $data['recipient_email'])->first();
        if ($requestExists && in_array($requestExists->status, [1, 3])) {
            abort(HTTPResponseEnum::BAD_REQUEST, 'A request in this state already exists');
        }
    }

    /**
     * Validates if the chat request can be accepted and initiates chat creation.
     * 
     * @param ChatRequest $chatRequest Chat request to validate and process.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException If validation fails or chat creation fails.
     */
    public function validateAndProcessChatRequest(ChatRequest $chatRequest): void
    {
        $this->ensureNotSelfSent($chatRequest);
        $this->ensureNoExistingChat($chatRequest);
        $this->initiateChatCreation($chatRequest);
    }

    /**
     * Ensures the chat request was not sent by the current user.
     * 
     * @param ChatRequest $chatRequest
     * @throws \Illuminate\Http\Exceptions\HttpResponseException If the sender is the current user.
     */
    private function ensureNotSelfSent(ChatRequest $chatRequest): void
    {
        if ($chatRequest->sender_email === Auth::user()->email) {
            abort(HTTPResponseEnum::FORBIDDEN, 'You cannot accept a chat request sent out by you.');
        }
    }

    /**
     * Checks for an existing chat between the sender and recipient to prevent duplicates.
     * 
     * @param ChatRequest $chatRequest
     * @throws \Illuminate\Http\Exceptions\HttpResponseException If an existing chat is found.
     */
    private function ensureNoExistingChat(ChatRequest $chatRequest): void
    {
        $existingChat = Chat::where('sender_id', $chatRequest->sender()->value('id'))
                            ->where('recipient_id', $chatRequest->recipient()->value('id'))
                            ->first();
        if ($existingChat) {
            abort(HTTPResponseEnum::BAD_REQUEST, 'A chat between these users already exists.');
        }
    }

    /**
     * Initiates the creation of a chat based on the chat request.
     * 
     * @param ChatRequest $chatRequest
     * @throws \Illuminate\Http\Exceptions\HttpResponseException If chat creation fails.
     */
    private function initiateChatCreation(ChatRequest $chatRequest): void
    {
        if (!$this->createChat($chatRequest)) {
            abort(HTTPResponseEnum::BAD_REQUEST, 'Failed to create chat.');
        }
    }
}