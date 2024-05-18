<?php

namespace App\Http\Controllers\Api\Chat;

use App\Models\Chat;
use App\Models\User;
use App\Lib\RequestHandler;
use Illuminate\Http\Request;
use App\Services\ChatService;
use App\Enums\HTTPResponseEnum;
use App\Http\Controllers\Controller;
use App\Services\ChatMessageService;
use Illuminate\Support\Facades\Auth;
use App\Services\EncryptRequestService;

class ChatMessageController extends Controller
{
    private User $authenticatedUser;

    public function __construct(
        public RequestHandler $requestHandler,
        public ChatMessageService $chatMessageService,
        public ChatService $chatService,
        public EncryptRequestService $encryptRequestService
    ) {
        $this->middleware(function ($request, $next) {
            $this->authenticatedUser = Auth::user();
            return $next($request);
        });
    }

    public function index(string $uuid, $secret)
    {
        return $this->requestHandler->handleException(function () use (
            $uuid,
            $secret
        ) {
            $chat = $this->chatService->validateUuid(
                $this->authenticatedUser,
                $uuid
            );
            $this->chatMessageService->validateUser(
                $this->authenticatedUser,
                $chat
            );
            $this->chatMessageService->validateChatSecret(
                $this->authenticatedUser,
                $chat,
                $secret
            );
            $chat = Chat::where("uuid", $uuid)->first();
            if ($chat) {
                $chatMessages = $chat->chatMessages()->with("messages")->get();
                $messages = $this->chatMessageService->processEncryptedMessage(
                    $chatMessages
                );
            }
            return response()->json($messages, HTTPResponseEnum::OK);
        });
    }

    public function create(string $uuid, Request $request)
    {
        return $this->requestHandler->handleException(function () use (
            $uuid,
            $request
        ) {
            $data = $this->requestHandler->validateRequest($request, [
                "body" => "required|string",
            ]);
            $chat = $this->chatService->validateUuid(
                $this->authenticatedUser,
                $uuid
            );
            $this->chatMessageService->validateUser(
                $this->authenticatedUser,
                $chat
            );
            $data = array_merge($data, [
                "title" => "chat",
                "secret" => $chat->chat_key,
            ]);
            $message = $this->encryptRequestService->encryptAndStoreDocument(
                $this->authenticatedUser,
                $data
            );
            $chatMessage = $this->chatMessageService->storeChatMessage(
                $message,
                $chat
            );
            return response()->json(
                ["uuid" => $chatMessage->uuid],
                HTTPResponseEnum::OK
            );
        });
    }
}
