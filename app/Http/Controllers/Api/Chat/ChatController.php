<?php

namespace App\Http\Controllers\Api\Chat;

use App\Enums\ChatActivityEnum;
use Throwable;
use App\Models\Chat;
use App\Models\User;
use App\Lib\RequestHandler;
use Illuminate\Http\Request;
use App\Enums\HTTPResponseEnum;
use App\Events\ChatActivity;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\handleExceptions;

/**
 * Handles chat-related operations for the API.
 */
class ChatController extends Controller
{
    /**
     * @var User Holds the authenticated user instance.
     */
    private User $authenticatedUser;

    /**
     * ChatRequestController constructor.
     *
     * @param User $user Injected User model to interact with user data.
     * @param ChatService $chatService Injected service to handle business logic related to chat requests.
     */
    public function __construct(
        public RequestHandler $requestHandler,
        public ChatService $chatService
    ) {
        $this->middleware(function ($request, $next) {
            $this->authenticatedUser = Auth::user();
            return $next($request);
        });
    }

    /**
     * Retrieves a list of chats for the authenticated user.
     *
     * @return JsonResponse List of chats or an error message.
     */
    public function index(): JsonResponse
    {
        return $this->requestHandler->handleException(function () {
            $chats = Chat::forUser($this->authenticatedUser->id)
                ->where("status", 1)
                ->get();
            foreach ($chats as $c) {
                if ($c["recipient_id"] !== $this->authenticatedUser->id) {
                    $c["other"] = User::find($c["recipient_id"]);
                    $c["role"] = "sender";
                } else {
                    $c["other"] = User::find($c["sender_id"]);
                    $c["role"] = "recipient";
                }
            }
            return response()->json($chats, JsonResponse::HTTP_OK);
        });
    }

    /**
     * Displays a specific chat identified by UUID.
     *
     * @param string $uuid The UUID of the chat to retrieve.
     * @return JsonResponse The requested chat or an error message.
     */
    public function show(string $uuid): JsonResponse
    {
        return $this->requestHandler->handleException(function () use ($uuid) {
            $chat = $this->chatService->validateUuid(
                $this->authenticatedUser,
                $uuid
            );
            if ($chat->recipient_id !== $this->authenticatedUser->id) {
                $chat->other = User::find($chat->recipient_id);
                $chat->role = "sender";
            } else {
                $chat->other = User::find($chat->sender_id);
                $chat->role = "recipient";
            }
            // $chat->role =
            //     $chat->sender_id === $this->authenticatedUser->id
            //         ? "sender"
            //         : "recipient";
            return response()->json($chat, JsonResponse::HTTP_OK);
        });
    }

    public function setChatSecret(string $uuid, Request $request)
    {
        return $this->requestHandler->handleException(function () use (
            $uuid,
            $request
        ) {
            $this->requestHandler->validateRequest($request, [
                "chat_secret" => "required|string|min:6",
            ]);
            $chat = $this->chatService->validateUuid(
                $this->authenticatedUser,
                $uuid
            );
            $this->chatService->setUserChatSecret(
                $this->authenticatedUser,
                $chat,
                $request->chat_secret
            );
            return response()->json($chat, JsonResponse::HTTP_CREATED);
        });
    }

    public function unlock(string $uuid)
    {
        return $this->requestHandler->handleException(function () use ($uuid) {
            $chat = Chat::where("uuid", $uuid)->firstOrFail();
            $chat->sender_lock = 0;
            $chat->recipient_lock = 0;
            $chat->save();
            event(new ChatActivity($chat, ChatActivityEnum::UNLOCKED));
            return response()->json(
                ["message" => "Chat unlocked successfully"],
                HTTPResponseEnum::OK
            );
        });
    }
}
